@extends('layouts.frontend.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card">
        <div class="card-body">
            <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                <span
                    class="me-1 rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 100px !important; height: 100px !important">
                    <i class="ti ti-check text-success" style="font-size: 100px"></i>
                </span>

                <h1>{{ $message }}</h1>
                <p>{{ $sub_message }}</p>
                <img src="{{ asset('assets/images/logos/logo.png') }}" width="100" alt="" />
            </div>
        </div>
    </div>
</div>
@endsection
