@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="{{ route('report-monthly') }}">{{ $title }}</a></li>
            <li>Bulanan</li>
          </ul>
        <div class="card">
            <div class="card-body">
                <a href="{{ route('report-monthly') }}" class="btn btn-warning">Kembali</a>
                <div id="chart" class="mt-5 d-flex align-items-center justify-content-center"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var categories = {!! json_encode($categories) !!};
    var series = {!! json_encode($series) !!};
    var options = {
        series: series,
        chart: {
          width: 380,
          type: 'pie',
        },
        labels: categories,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
@endsection
