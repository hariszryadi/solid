@extends('layouts.frontend.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card">
        <div class="card-body">
            <a href="{{ route('index') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="{{ asset('assets/images/logos/logo.png') }}" width="180" alt="">
            </a>
            <div class="text-center">
                <h2 class="text-primary fw-semibold">Solusi Limbah Indonesia</h2>
                <p class="text-primary fw-semibold">Aplikasi monitoring pengolahan sampah</p>
            </div>
            <div class="d-flex justify-content-between" style="overflow: auto">
                <a href="#" class="btn btn-primary" style="padding: 10px 40px">Login</a>
                <a href="#" class="btn btn-primary" style="padding: 10px 40px">Register</a>
            </div>
        </div>
    </div>
</div>
@endsection
