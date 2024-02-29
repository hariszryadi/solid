@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li><a href="{{ route('account.index') }}">{{ $title }}</a></li>
                <li>Edit {{ $title }}</li>
              </ul>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('account.update', $account->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $account->name) }}" placeholder="Nama">
                                @error('name')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $account->email) }}" placeholder="Email">
                                @error('email')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                                @error('password')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="" selected disabled>Pilih</option>
                                    <option value="user" {{ old('role', $account->role) == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="pic" {{ old('role', $account->role) == 'pic' ? 'selected' : '' }}>PIC</option>
                                </select>
                                @error('role')
                                    <div class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label for="organization" class="form-label">Organization</label>
                                <select name="organization" id="organization" class="form-control">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($organizations as $item)
                                        <option value="{{ $item->id }}" {{ old('organization', $account->organization_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('organization')
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
