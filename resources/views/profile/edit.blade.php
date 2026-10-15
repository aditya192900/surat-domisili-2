<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- INFO PROFIL --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- HAPUS AKUN (terpisah dengan peringatan jelas) --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" style="border:2px solid #fecaca;">
                <div class="max-w-xl">
                    <h2 style="font-size:18px; font-weight:700; color:#991b1b; margin-bottom:8px;">
                        ⚠️ Zona Berbahaya — Hapus Akun Permanen
                    </h2>
                    <p style="font-size:13px; color:#7f1d1d; margin-bottom:16px; line-height:1.6;">
                        <strong>PERHATIAN:</strong> Setelah akun dihapus, semua data akan hilang permanen dan 
                        <strong>TIDAK BISA dikembalikan</strong>. Jangan klik tombol di bawah kecuali Anda yakin 
                        ingin menghapus akun sepenuhnya.
                    </p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>