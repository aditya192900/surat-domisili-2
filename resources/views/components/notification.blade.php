@if (session('success') || session('error') || session('warning') || session('info'))
<div id="notif-container"
     style="position:fixed; top:80px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; max-width:400px; width:calc(100% - 40px);">

    @php
        $notifs = [
            'success' => ['icon' => '✓', 'title' => 'Berhasil', 'bg' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)'],
            'error'   => ['icon' => '✕', 'title' => 'Gagal',    'bg' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'],
            'warning' => ['icon' => '!', 'title' => 'Perhatian', 'bg' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'],
            'info'    => ['icon' => 'i', 'title' => 'Informasi', 'bg' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'],
        ];
    @endphp

    @foreach ($notifs as $key => $cfg)
        @if (session($key))
            <div class="notif-item"
                 style="background:{{ $cfg['bg'] }}; color:white; padding:14px 18px; border-radius:12px;
                        box-shadow:0 10px 25px rgba(0,0,0,0.18); display:flex; align-items:flex-start;
                        gap:12px; animation:notifSlide 0.4s ease-out; position:relative; overflow:hidden;">
                <div style="flex-shrink:0; width:34px; height:34px; background:rgba(255,255,255,0.25);
                            border-radius:50%; display:flex; align-items:center; justify-content:center;
                            font-size:18px; font-weight:bold;">
                    {{ $cfg['icon'] }}
                </div>
                <div style="flex:1; padding-right:8px;">
                    <div style="font-weight:600; margin-bottom:2px; font-size:14px;">
                        {{ $cfg['title'] }}
                    </div>
                    <div style="font-size:13px; opacity:0.95; line-height:1.4;">
                        {{ session($key) }}
                    </div>
                </div>
                <button onclick="this.parentElement.remove()"
                        style="background:none; border:none; color:white; cursor:pointer;
                               font-size:22px; padding:0; margin-left:4px; opacity:0.75; line-height:1;">
                    ×
                </button>
                <div style="position:absolute; bottom:0; left:0; height:3px;
                            background:rgba(255,255,255,0.55); animation:notifProgress 5s linear;"></div>
            </div>
        @endif
    @endforeach
</div>

<style>
    @keyframes notifSlide {
        from { transform: translateX(120%); opacity: 0; }
        to   { transform: translateX(0);    opacity: 1; }
    }
    @keyframes notifProgress {
        from { width: 100%; }
        to   { width: 0%; }
    }
    @keyframes notifFadeOut {
        from { opacity: 1; transform: translateX(0); }
        to   { opacity: 0; transform: translateX(120%); }
    }
    @media (max-width: 640px) {
        #notif-container { right: 10px !important; left: 10px !important; width: auto !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.notif-item').forEach(function (notif, index) {
            setTimeout(function () {
                notif.style.animation = 'notifFadeOut 0.4s ease-in forwards';
                setTimeout(function () { notif.remove(); }, 400);
            }, 5000 + (index * 300));
        });
    });
</script>
@endif