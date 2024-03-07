@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li><a href="{{ route('transaction.index') }}">{{ $title }}</a></li>
                <li>Edit {{ $title }}</li>
              </ul>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('transaction.update', $transaction->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="account" class="form-label">Akun</label>
                                <select name="account" id="account" class="form-control">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}" {{ old('account', $transaction->account_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('account')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="weight" class="form-label">Berat (kg)</label>
                                <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $transaction->weight) }}" placeholder="Berat" step="any">
                                @error('weight')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}" {{ old('category', $transaction->category_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
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
