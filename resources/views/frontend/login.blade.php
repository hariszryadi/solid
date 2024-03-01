@extends('layouts.frontend.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card" style="width: 400px">
        <div class="card-body">
            <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="{{ asset('assets/images/logos/logo.png') }}" width="180" alt="">
            </a>
            <form method="POST" action="#">
                <h3 class="text-primary fw-semibold">Selamat Datang!</h3>
                <p class="text-primary">Silahkan masuk ke aplikasi kami</p>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" autofocus>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Masuk</button>
            </form>
        </div>
    </div>
</div>
@endsection
