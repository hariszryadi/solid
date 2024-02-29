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
                            <th>Email</th>
                            <th>Role</th>
                            <th>Instansi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($accounts as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->role == 'pic' ? 'PIC' : 'User' }}</td>
                                <td>{{ $item->organization->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('account.edit', $item->id) }}" class="btn btn-sm btn-success">
                                        <span>
                                            <i class="ti ti-pencil"></i>
                                        </span>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete" data-url="{{ route('account.destroy', $item->id) }}">
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

