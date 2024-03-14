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
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex" style="gap: 4px">
                        <a href="{{ route('report-daily') }}" class="btn btn-warning">Kembali</a>
                        <button type="button" class="btn btn-danger" id="printPdf" disabled>Download PDF</button>
                    </div>
                    <p>{{ $date }}</p>
                </div>
                <div id="chart" class="mt-5 d-flex align-items-center justify-content-center"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script>
    var categories = {!! json_encode($categories) !!};
    var series = {!! json_encode($series) !!};
    var organization = {!! json_encode($organization) !!};
    var date = {!! json_encode($date) !!};
    var options = {
        series: series,
        chart: {
          width: 380,
          type: 'pie',
          events: {
            mounted: function(chartContext, config) {
              $('#printPdf').removeAttr('disabled')
            }
          }
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

    $('#printPdf').on('click', function () {
        chart.dataURI().then(({ imgURI, blob }) => {
            var pdf = new jsPDF('landscape', 'pt', 'a5');
            var pdfWidth = pdf.internal.pageSize.getWidth();
            var pdfHeight = pdf.internal.pageSize.getHeight();
            var imgWidth = 280;
            var imgHeight = 180;
            var imgX = 50;
            var imgY = 50;
            pdf.addImage(imgURI, 'PNG', imgX, imgY, imgWidth, imgHeight);

            var text = 'Report volume debit sampah ' + organization + ' ' + date;
            var textWidth = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
            var textX = imgX + 10;
            var textY = imgY - 5;
            pdf.setTextColor(0, 0, 0);
            pdf.setFontSize(11);
            pdf.text(text, textX, textY);
            pdf.save('report-monthly.pdf');
        })
    })
</script>
@endsection
