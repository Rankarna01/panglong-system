@extends('admin.exports.layout')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th>Tanggal</th>
            <th>Referensi</th>
            <th>Nama Barang</th>
            <th>Alasan</th>
            <th>Kasir / Admin</th>
            <th class="text-center">Jumlah Keluar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stockOuts as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
            <td>{{ $item->reference }}</td>
            <td>{{ $item->product->name ?? '-' }}</td>
            <td>{{ $item->reason ?? '-' }}</td>
            <td>{{ $item->user->name ?? '-' }}</td>
            <td class="text-center">{{ $item->qty }} {{ $item->product->baseUnit->short_name ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
