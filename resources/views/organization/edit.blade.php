@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li><a href="{{ route('organization.index') }}">{{ $title }}</a></li>
                <li>Edit {{ $title }}</li>
              </ul>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('organization.update', $organization->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $organization->name) }}" placeholder="Nama">
                                @error('name')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $organization->address) }}" placeholder="Alamat">
                                @error('address')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
