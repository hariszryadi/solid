@extends('layouts.frontend.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card" style="width: 400px; z-index: 1">
        <div class="card-body">
            <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="{{ asset('assets/images/logos/logo.png') }}" width="180" alt="">
            </a>
            <form method="POST" action="#">
                <h3 class="text-primary fw-semibold">Daftar</h3>
                <p class="text-primary">Silahkan isi untuk mendaftarkan akun Anda</p>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nama" autofocus>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password">
                </div>
                <div class="mb-4">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="form-control" id="role">
                        <option value="" selected disabled>Pilih</option>
                        <option value="user">User</option>
                        <option value="pic">PIC</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="organization" class="form-label">Instansi</label>
                    <select name="organization" class="form-control select2" id="organization">
                        <option value="" selected disabled>Pilih</option>
                        @foreach ($response as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a class="btn btn-light w-50 py-8 fs-4 m-2 rounded-2">Kembali</a>
                    <button type="submit" class="btn btn-primary w-50 py-8 fs-4 m-2 rounded-2">Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection