@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li>{{ $title }}</li>
        </ul>
        <div class="card">
            <div class="card-body table-responsive">
                @include('helper.alert')
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Berat</th>
                            <th>Categori</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($transactions as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->account->name }}</td>
                                <td>{{ $item->organization->name }}</td>
                                <td>{{ $item->weight }} kg</td>
                                <td>{{ $item->category->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('transaction.edit', $item->id) }}" class="btn btn-sm btn-success">
                                        <span>
                                            <i class="ti ti-pencil"></i>
                                        </span>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete" data-url="{{ route('transaction.destroy', $item->id) }}">
                                        <span>
                                            <i class="ti ti-trash"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

