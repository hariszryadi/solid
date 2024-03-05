@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="{{ route('report-daily') }}">{{ $title }}</a></li>
            <li>Harian</li>
          </ul>
        <div class="card">
            <div class="card-body">
                <a href="{{ route('report-daily') }}" class="btn btn-warning">Kembali</a>
                <div id="chart" class="mt-5"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var dates = {!! json_encode($dates) !!};
    var organic = {!! json_encode($organic) !!};
    var anorganic = {!! json_encode($anorganic) !!};
    var b3 = {!! json_encode($b3) !!};
    var options = {
        series: [{
          name: 'Organik',
          data: organic
        }, {
          name: 'Anorganik',
          data: anorganic
        }, {
          name: 'B3',
          data: b3
        }],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: false
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        plotOptions: {
          bar: {
            horizontal: false,
            // borderRadius: 10,
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        xaxis: {
          categories: dates,
        },
        legend: {
          position: 'right',
          offsetY: 10
        },
        fill: {
          opacity: 1
        },
        colors: ['#2EA3D5', '#D92626', '#FFAE1F']
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
@endsection
