@extends('admin.exports.layout')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th class="text-center">Stok Sisa</th>
            <th class="text-center">Satuan Dasar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->name ?? '-' }}</td>
            <td class="text-center">{{ $item->stock }}</td>
            <td class="text-center">{{ $item->unit->name ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
