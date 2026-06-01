<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .kop-wrapper { width: 100%; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat { width: 100%; border-collapse: collapse; border: none; margin: 0; }
        .kop-surat td { border: none; padding: 0; vertical-align: middle; }
        .kop-logo { width: 20%; text-align: left; }
        .kop-logo img { max-height: 70px; }
        .kop-tengah { width: 60%; text-align: center; }
        .kop-tengah h1 { margin: 0; font-size: 24px; color: #5D4037; text-transform: uppercase; }
        .kop-kanan { width: 20%; text-align: right; }
        .kop-kanan p { margin: 2px 0; font-size: 11px; color: #666; line-height: 1.4; }
        
        .laporan-title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .data-table th { background-color: #f4f4f4; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>

    @php
        $setting = \App\Models\Setting::first();
        $base64 = null;
        if($setting && $setting->logo_path) {
            $path = storage_path('app/public/' . $setting->logo_path);
            if(file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
    @endphp

    <div class="kop-wrapper">
        <table class="kop-surat">
            <tr>
                <td class="kop-logo">
                    @if($base64)
                        <img src="{{ $base64 }}" alt="Logo">
                    @endif
                </td>
                <td class="kop-tengah">
                    <h1>{{ $setting->app_name ?? 'PANGLONG SISTEM' }}</h1>
                </td>
                <td class="kop-kanan">
                    <p>{{ $setting->address ?? 'Alamat Belum Diatur' }}</p>
                    <p>Telepon: {{ $setting->phone ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="laporan-title">
        {{ $title ?? 'Laporan' }}
        <div style="font-size: 11px; font-weight: normal; margin-top: 5px;">Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</div>
    </div>

    @yield('content')

    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Admin / Manajer</strong></p>
    </div>

</body>
</html>
