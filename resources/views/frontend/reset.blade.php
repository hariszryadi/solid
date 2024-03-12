@extends('layouts.frontend.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card" style="width: 400px; z-index: 1">
        <div class="card-body">
            <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="{{ asset('assets/images/logos/logo.png') }}" width="180" alt="">
            </a>
            @include('helper.alert')
            <form method="POST" action="{{ route('reset.password') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <h3 class="text-primary fw-semibold">Reset Password</h3>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Reset Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
