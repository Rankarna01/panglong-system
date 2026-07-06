@extends('admin.exports.layout')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th>Tanggal</th>
            <th>No. Invoice</th>
            <th>Kasir</th>
            <th class="text-right">Total Item</th>
            <th class="text-right">Total Transaksi (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grandTotal = 0;
        @endphp
        @foreach($sales as $index => $item)
        @php
            $grandTotal += $item->total_price;
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</td>
            <td>{{ $item->invoice }}</td>
            <td>{{ $item->user->name ?? '-' }}</td>
            <td class="text-right">{{ $item->total_item }}</td>
            <td class="text-right">{{ number_format($item->total_price, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-right">TOTAL KESELURUHAN</th>
            <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
@endsection
