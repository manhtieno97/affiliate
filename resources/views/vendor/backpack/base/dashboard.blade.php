@extends(backpack_view('blank'))
@section('header')
    <section class="content-header">
        <h1 class="ml-4 p-1">
            {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mt-5">
                <h4>Biểu đồ tổng quan crawl dữ liệu</h4>
                <figure class="highcharts-figure">
                    <div id="crawl"></div>
                </figure>
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-8 mt-5">
                <h4>Biểu đồ tổng quan upload dữ liệu</h4>
                <figure class="highcharts-figure">
                    <div id="upload"></div>
                </figure>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </div>
@endsection

@section('before_styles')

@endsection
@section('after_scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script type="text/javascript">
        // Create the chart
        Highcharts.chart('crawl', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'crawl'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Total Question'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:,.0f} '
                    }
                }
            },

            tooltip: {
                formatter:function(){
                    return this.point.series.name + ': <b>' + Highcharts.numberFormat(this.point.options.y,0,'.',' ') + '</b><br/>' +'<br/>';
                }
            },

            series: [
                {
                    name: "Question",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Total",
                            y: <?php echo $crawl['total'] ?>,
                        },
                        {
                            name: "Default",
                            y: <?php echo $crawl['default'] ?>,
                        },
                        {
                            name: "Success",
                            y: <?php echo $crawl['success'] ?>,
                        },
                        {
                            name: "Error",
                            y: <?php echo $crawl['error'] ?>,
                        }
                    ]
                }
            ],

        });
    </script>
    <script type="text/javascript">
        // Create the chart
        Highcharts.chart('upload', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Upload'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Total Question'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:,.0f} '
                    }
                }
            },

            tooltip: {

                formatter:function(){
                    return this.point.series.name + ': <b>' + Highcharts.numberFormat(this.point.options.y,0,'.',' ') + '</b><br/>' +'<br/>';
                }
            },

            series: [
                {
                    name: "Question",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Total",
                            y: <?php echo $crawl['success'] ?>,
                        },
                        {
                            name: "Default",
                            y: <?php echo $upload['default'] ?>,
                        },
                        {
                            name: "Success",
                            y: <?php echo $upload['success'] ?>,
                        },
                        {
                            name: "Error",
                            y: <?php echo $upload['error'] ?>,
                        }
                    ]
                }
            ],

        });
    </script>
@endsection

