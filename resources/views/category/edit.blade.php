@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li><a href="{{ route('category.index') }}">{{ $title }}</a></li>
                <li>Edit {{ $title }}</li>
              </ul>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('category.update', $category->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Nama">
                                @error('name')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $category->description) }}" placeholder="Deskripsi">
                                @error('description')
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
