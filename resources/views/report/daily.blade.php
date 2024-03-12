@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li>Harian</li>
          </ul>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('report-daily-result') }}" method="POST">
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
                            <div class="form-group @error('date') has-error @enderror">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            </div>
                            @error('date')
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

