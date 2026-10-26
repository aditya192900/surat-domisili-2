<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // ⚠️ EMAIL TIDAK DIVALIDASI/DIIZINKAN DIUBAH DARI SINI
        // Perubahan email harus melalui Ketua RT / Admin Kelurahan.
        $rules = [
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'foto'          => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string|max:500',
            'nip'           => 'nullable|string|max:30',
            'jabatan'       => 'nullable|string|max:100',
            'ttd'           => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'stempel'       => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        ];

        $validated = $request->validate($rules);

        // 🔒 KUNCI EMAIL — paksa tetap pakai email lama, apapun yang dikirim user
        // Ini mencegah user "nakal" yang menghapus atribut readonly via DevTools.
        $validated['email'] = $user->email;

        // Upload foto profil
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('local')->exists($user->foto)) {
                Storage::disk('local')->delete($user->foto);
            }
            $validated['foto'] = $request->file('foto')->store('private/foto-profil', 'local');
        } else {
            unset($validated['foto']);
        }

        // Upload TTD - konversi ke PNG transparan resolusi tinggi
        if ($request->hasFile('ttd')) {
            if ($user->ttd_path && Storage::disk('local')->exists($user->ttd_path)) {
                Storage::disk('local')->delete($user->ttd_path);
            }
            $path = $this->processImageUpload($request->file('ttd'), 'ttd', 1200);
            if ($path) {
                $validated['ttd_path'] = $path;
            }
        }
        unset($validated['ttd']);

        // Upload stempel - konversi ke PNG transparan resolusi tinggi
        if ($request->hasFile('stempel')) {
            if ($user->stempel_path && Storage::disk('local')->exists($user->stempel_path)) {
                Storage::disk('local')->delete($user->stempel_path);
            }
            $path = $this->processImageUpload($request->file('stempel'), 'stempel', 1200);
            if ($path) {
                $validated['stempel_path'] = $path;
            }
        }
        unset($validated['stempel']);

        $user->fill($validated);
        $user->save();

        ActivityLogger::log('update_profil', "User {$user->name} memperbarui profil", $user);

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Konversi gambar ke PNG transparan resolusi tinggi.
     * Menghasilkan file yang tajam saat dicetak.
     */
    private function processImageUpload($file, $folder, $maxWidth = 1200): ?string
    {
        try {
            // Load image dari berbagai format (JPG, PNG)
            $source = @imagecreatefromstring(file_get_contents($file->getRealPath()));
            if (!$source) {
                return null;
            }

            $width = imagesx($source);
            $height = imagesy($source);

            // Resize kalau terlalu besar (biar file tidak berat)
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = intval($height * ($maxWidth / $width));

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($source);
                $source = $resized;
            }

            // Nama file unik
            $filename = uniqid('sig_') . '_' . time() . '.png';
            $relativePath = $folder . '/' . $filename;
            $fullPath = storage_path('app/private/' . $relativePath);

            // Pastikan folder ada
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Save sebagai PNG transparan dengan kompresi minimal (kualitas tertinggi)
            imagealphablending($source, false);
            imagesavealpha($source, true);
            imagepng($source, $fullPath, 0); // 0 = tanpa kompresi, kualitas tertinggi
            imagedestroy($source);

            return $relativePath;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function hapusFoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->foto && Storage::disk('local')->exists($user->foto)) {
            Storage::disk('local')->delete($user->foto);
        }
        $user->update(['foto' => null]);

        ActivityLogger::log('hapus_foto_profil', "User {$user->name} menghapus foto profil", $user);

        return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }

    public function hapusTtd(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->ttd_path && Storage::disk('local')->exists($user->ttd_path)) {
            Storage::disk('local')->delete($user->ttd_path);
        }
        $user->update(['ttd_path' => null]);

        ActivityLogger::log('hapus_ttd', "User {$user->name} menghapus tanda tangan digital", $user);

        return Redirect::route('profile.edit')->with('success', 'Tanda tangan berhasil dihapus.');
    }

    public function hapusStempel(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->stempel_path && Storage::disk('local')->exists($user->stempel_path)) {
            Storage::disk('local')->delete($user->stempel_path);
        }
        $user->update(['stempel_path' => null]);

        ActivityLogger::log('hapus_stempel', "User {$user->name} menghapus stempel digital", $user);

        return Redirect::route('profile.edit')->with('success', 'Stempel berhasil dihapus.');
    }

    public function foto(Request $request, $userId = null)
    {
        $targetUser = $userId ? \App\Models\User::findOrFail($userId) : $request->user();

        if (!$targetUser->foto) {
            abort(404);
        }

        $fullPath = storage_path('app/private/' . $targetUser->foto);

        if (!file_exists($fullPath)) {
            abort(404);
        }

                return response()->file($fullPath, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    public function ttd(Request $request, $userId = null)
    {
        $targetUser = $userId ? \App\Models\User::findOrFail($userId) : $request->user();

        if (!$targetUser->ttd_path) {
            abort(404);
        }

        $fullPath = storage_path('app/private/' . $targetUser->ttd_path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

                return response()->file($fullPath, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    public function stempel(Request $request, $userId = null)
    {
        $targetUser = $userId ? \App\Models\User::findOrFail($userId) : $request->user();

        if (!$targetUser->stempel_path) {
            abort(404);
        }

        $fullPath = storage_path('app/private/' . $targetUser->stempel_path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

                return response()->file($fullPath, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}