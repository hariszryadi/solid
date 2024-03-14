@extends('layouts.app')

@section('content')
<div class="row mt-5">
    <div class="col-lg-12">
        <div class="row" style="display: flex; align-items: stretch;">
            <div class="col-lg-3">
                <div class="card overflow-hidden" style="height: 85%;">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Persentase Error</h5>
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h1 class="fw-semibold mb-3">{{ $error }} %</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card overflow-hidden" style="height: 85%;">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Volume Debit Harian</h5>
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4 class="fw-semibold mb-3">{{ $sum }} kg</h4>
                                <div class="d-flex align-items-center mb-3">
                                    <p class="fs-3 mb-0">{{ $date }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-center">
                                    <div id="chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Transaksi terakhir</h5>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">#</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Tanggal</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Instansi</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Berat</h6>
                                </th>
                                <th class="border-bottom-0 text-center">
                                    <h6 class="fw-semibold mb-0">Kategori</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($transactions as $item)
                                <tr>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold">{{ $i++ }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p class="fw-normal">{{ $item->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p class="fw-normal">{{ $item->account->name }}</p>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p class="fw-normal">{{ $item->organization->name }}</p>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p class="fw-normal">{{ $item->weight }} kg</p>
                                    </td>
                                    <td class="border-bottom-0 d-flex justify-content-center">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($item->category_id == 1)
                                                <span class="badge bg-info rounded-3 fw-semibold">{{ $item->category->name }}</span>
                                            @endif
                                            @if ($item->category_id == 2)
                                                <span class="badge bg-danger rounded-3 fw-semibold">{{ $item->category->name }}</span>
                                            @endif
                                            @if ($item->category_id == 3)
                                                <span class="badge bg-warning rounded-3 fw-semibold">{{ $item->category->name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
          width: 200,
          type: 'pie',
          events: {
            mounted: function(chartContext, config) {
              $('#printPdf').removeAttr('disabled')
            }
          }
        },
        labels: categories,
        legend: {
            show: false
        },
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
