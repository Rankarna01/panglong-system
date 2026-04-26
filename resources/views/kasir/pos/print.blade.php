<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Struk - {{ $sale->invoice }}</title>
    <style>
        @page { margin: 10px; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 10px; line-height: 1.3; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
    </style>
</head>
<body>
    <div class="text-center">
        <div style="font-size: 14px; font-weight: bold; margin-bottom: 3px;">FLOWINTI PANGLONG</div>
        <div>Jl. Contoh Alamat No. 123, Medan<br>Telp: 0812-3456-7890</div>
    </div>
    <div class="divider"></div>
    <table>
        <tr><td style="width: 35%;">No. Struk</td><td>: {{ $sale->invoice }}</td></tr>
        <tr><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Kasir</td><td>: {{ explode(' ', $sale->user->name)[0] }}</td></tr>
    </table>
    <div class="divider"></div>
    <table>
        @foreach($sale->details as $item)
        <tr>
            <td colspan="2" class="font-bold">{{ $item->product->name ?? 'Barang Terhapus' }}</td>
        </tr>
        <tr>
            <td style="width: 50%;">{{ (float)$item->qty }} {{ $item->product->baseUnit->short_name ?? '' }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right" style="width: 50%;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <div class="divider"></div>
   <table>
        @foreach($sale->details as $item)
        <tr>
            <td colspan="2" class="font-bold">{{ $item->product->name ?? 'Barang Terhapus' }}</td>
        </tr>
        <tr>
            <td style="width: 50%;">
                {{ rtrim(rtrim(number_format($item->qty, 2, ',', '.'), '0'), ',') }} {{ $item->product->baseUnit->short_name ?? '' }} x {{ number_format($item->price, 0, ',', '.') }}
            </td>
            <td class="text-right" style="width: 50%;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <div class="divider"></div>
    <div class="text-center" style="margin-top: 15px;">Terima Kasih Atas Kunjungan Anda</div>
</body>
</html>