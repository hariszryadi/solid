@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li>{{ $title }}</li>
        </ul>
        <div class="card">
            <div class="card-body">
                @include('helper.alert')
                <div class="text-right">
                    <a href="{{ route('organization.create') }}" class="btn btn-info"><span><i class="ti ti-plus"></i></span>&nbsp;Tambah</a>
                </div>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($organizations as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->address }}</td>
                                <td class="text-center">
                                    <a href="{{ route('organization.edit', $item->id) }}" class="btn btn-sm btn-success">
                                        <span>
                                            <i class="ti ti-pencil"></i>
                                        </span>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete" data-url="{{ route('organization.destroy', $item->id) }}">
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

