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
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex" style="gap: 4px">
                        <a href="{{ route('report-monthly') }}" class="btn btn-warning">Kembali</a>
                        <button type="button" class="btn btn-danger" id="printPdf" disabled>Download PDF</button>
                    </div>
                    <p>{{ $month . ' ' . $year }}</p>
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
    var dates = {!! json_encode($dates) !!};
    var organic = {!! json_encode($organic) !!};
    var anorganic = {!! json_encode($anorganic) !!};
    var b3 = {!! json_encode($b3) !!};
    var month = {!! json_encode($month) !!};
    var year = {!! json_encode($year) !!};
    var organization = {!! json_encode($organization) !!};
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
          },
          events: {
            mounted: function(chartContext, config) {
              $('#printPdf').removeAttr('disabled')
            }
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
                enabled: false,
                // style: {
                //   fontSize: '13px',
                //   fontWeight: 900
                // }
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

    $('#printPdf').on('click', function () {
        chart.dataURI().then(({ imgURI, blob }) => {
            var pdf = new jsPDF('landscape', 'pt', 'a5');
            var pdfWidth = pdf.internal.pageSize.getWidth();
            var pdfHeight = pdf.internal.pageSize.getHeight();
            var imgWidth = 500;
            var imgHeight = 200;
            var imgX = 50;
            var imgY = 50;
            pdf.addImage(imgURI, 'PNG', imgX, imgY, imgWidth, imgHeight);

            var text = 'Diagram volume debit sampah ' + organization + ' Bulan ' + month + ' Tahun ' + year;
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
