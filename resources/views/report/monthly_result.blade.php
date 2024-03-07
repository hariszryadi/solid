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
                <button type="button" class="btn btn-danger" id="printPdf" disabled>Print PDF</button>
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
            var pdf = new jsPDF('landscape', 'pt', 'a4');
            var pdfWidth = pdf.internal.pageSize.getWidth();
            var pdfHeight = pdf.internal.pageSize.getHeight();
            var imgWidth = 325;
            var imgHeight = 200;
            var imgX = (pdfWidth - imgWidth) / 2;
            var imgY = (pdfHeight - imgHeight) / 2;
            pdf.addImage(imgURI, 'SVG', imgX, imgY, imgWidth, imgHeight);

            var text = 'Debit Volume Sampah Harian berdasarkan Kategorinya';
            var textWidth = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
            var textX = imgX + (imgWidth - textWidth) / 2;
            var textY = imgY + -30;
            pdf.setTextColor(0, 0, 0);
            pdf.setFontSize(16);
            pdf.text(text, textX, textY);
            pdf.save('report-daily.pdf');
        })
    })
</script>
@endsection
