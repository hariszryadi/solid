@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="{{ route('category.index') }}">{{ $title }}</a></li>
            <li>Bulanan</li>
          </ul>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('report-monthly-result') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="form-group @error('organization') has-error @enderror">
                                <label for="organization" class="form-label">Instansi</label>
                                <select name="organization" class="form-control select2" id="organization">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($organizations as $item)
                                        <option value="{{ $item->id }}" {{ old('organization') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('organization')
                                <div class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="col-3 mb-3">
                            <div class="form-group @error('month') has-error @enderror">
                                <label for="month" class="form-label">Bulan</label>
                                <select name="month" class="form-control select2" id="month">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($months as $key => $item)
                                        <option value="{{ $key }}" {{ old('month') == $key ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('month')
                                <div class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="col-3 mb-3">
                            <div class="form-group @error('year') has-error @enderror">
                                <label for="year" class="form-label">Tahun</label>
                                <select name="year" class="form-control select2" id="year">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($years as $item)
                                        <option value="{{ $item->year }}" {{ old('year') == $item->year ? 'selected' : '' }}>{{ $item->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year')
                                <div class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

