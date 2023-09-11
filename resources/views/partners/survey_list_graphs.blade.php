@extends('layout.partners.list_master')

@section('content')
    <?php $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->get()->first(); ?>

    <style>
        input{
           border-radius: 20px;
        }
        .avatar {
          vertical-align: middle;
          width: 50px;
          height: 50px;
          border-radius: 50%;
        }
        .imageUpload
        {
            display: none;
        }

        .profileImage
        {
            /* margin-top: -40px; */
            cursor: pointer;
            width: 100%;
        }

        #profile-container {
            margin: 20px auto;
            width: 130px;
            height: 130px;
            color: white;
            justify-content: center;
            border: 1px solid #8f8989;
            overflow: hidden;
        }

        #profile-container img {
            width: 150px;
            height: 150px;
           
        }
    </style>
    <!--**********************************
        Chat box End
    ***********************************-->
    
    <div class="content-body">
        <div class="container-fluid">
            <div class="col-md-12 mb-n5">
                <div class="col-sm-12 p-md-0">
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        @section('titleBar')
                        <span class="ml-2">Manage Survey</span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <div class="col-lg-12">
                                        @if($survey_list_graphs->image)  
                                        <img src="{{ asset($survey_list_graphs->image)}}" width="80px" height="80px">
                                        @else
                                        <img src="{{asset('uploads/placeholder/default.png')}}" height="80px" width="80px">
                                        @endif

                                        {{$survey_list_graphs->name}} 

                                        <label class="btn btn-primary pull-right">
                                            {{$survey_list_graphs->status}}
                                        </label>

                                        <br>

                                        <label class="pull-right">
                                            Last Updated: {{$survey_list_graphs->updated_at}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- row -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-sm-flex d-block pb-0 border-0">
                                <div class="d-flex align-items-center">
                                    <span class="p-3 mr-3 rounded bg-warning">
                                        <i class="fa fa-usd" aria-hidden="true" style="color: white;"></i>
                                    </span>
                                    <div class="mr-auto pr-3">
                                        <h4 class="text-black fs-20">Survey Answers</h4>
                                        <p class="fs-13 mb-0 text-black">Take a look at your survey answers.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div id="chartBar"></div>
                            </div>
                        </div>
                    </div>

                    <!--
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header d-sm-flex d-block pb-0 border-0">
                                <div class="d-flex align-items-center">
                                    <span class="p-3 mr-3 rounded bg-warning">
                                        <i class="fa fa-usd" aria-hidden="true" style="color: white;"></i>
                                    </span>
                                    <div class="mr-auto pr-3">
                                        <h4 class="text-black fs-20">Survey Answers</h4>
                                        <p class="fs-13 mb-0 text-black">Take a look at your survey answers.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div id="pieChart"></div>
                                <ul class="d-flex flex-wrap">
                                    <li class="mr-5 mb-2">
                                        <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="19" height="19" rx="9.5" fill="#0F172A"></rect>
                                        </svg>
                                        <span class="fs-12 text-black">Total Hired</span>
                                    </li>
                                    <li class="mr-5 mb-2">
                                        <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="19" height="19" rx="9.5" fill="#A87B5D"></rect>
                                        </svg>
                                        <span class="fs-12 text-black">Total Cancelled</span>
                                    </li>
                                    <li class="mr-5 mb-2">
                                        <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="19" height="19" rx="9.5" fill="#E5E5E5"></rect>
                                        </svg>
                                        <span class="fs-12 text-black">Total Pending</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        -->
                    </div>
                    <!-- row -->
                </div>
                <!-- row -->                    
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <link href="{{url('public')}}/vendor/chartist/css/chartist.min.css" rel="stylesheet" type="text/css"/>
    <script src="{{url('public')}}/vendor/chart.js/Chart.bundle.min.js" type="text/javascript"></script>
    <script src="{{url('public')}}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
    <script src="{{url('public')}}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
    <?php $survey_list_qs = DB::table('survey_list_qs')->select('*')->where('survey_list_id', $survey_list_graphs->survey_list_id)->get(); ?>
    <script type="text/javascript">
        (function($) {
            /* "use strict" */
            var dzChartlist = function(){   
                var screenWidth = $(window).width();
                var chartBar = function(){
                var optionsArea = {
                    series: [
                        <?php foreach($survey_list_qs as $questions){ ?>
                        <?php $survey_list_qs_answers_1 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_2 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_3 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_4 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_5 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_6 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_7 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_8 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_9 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_10 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_11 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        <?php $survey_list_qs_answers_12 = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $questions->survey_list_qs_id)->count(); ?>
                        {
                            name: "<?php echo $questions->name; ?>",
                            data: [
                                <?php echo $survey_list_qs_answers_1; ?>,
                                <?php echo $survey_list_qs_answers_2; ?>,
                                <?php echo $survey_list_qs_answers_3; ?>,
                                <?php echo $survey_list_qs_answers_4; ?>,
                                <?php echo $survey_list_qs_answers_5; ?>,
                                <?php echo $survey_list_qs_answers_6; ?>,
                                <?php echo $survey_list_qs_answers_7; ?>,
                                <?php echo $survey_list_qs_answers_8; ?>,
                                <?php echo $survey_list_qs_answers_9; ?>,
                                <?php echo $survey_list_qs_answers_10; ?>,
                                <?php echo $survey_list_qs_answers_11; ?>,
                                <?php echo $survey_list_qs_answers_12; ?>,
                            ]
                        },
                        <?php } ?>
                    ],
                    chart: {
                    height: 350,
                    type: 'area',
                    group: 'social',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                      },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: [4, 4, 4],
                        colors:['#C046D3','#1EA7C5','#FF9432'],
                        curve: 'straight'
                    },
                    legend: {
                        show:false,
                        tooltipHoverFormatter: function(val, opts) {
                            return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
                        },
                        
                        markers: {
                            fillColors:['#C046D3','#1EA7C5','#FF9432'],
                            width: 19,
                            height: 19,
                            strokeWidth: 0,
                            radius: 19
                        }
                    },
                    markers: {
                        size: [8,8,6],
                        strokeWidth: [0,0,4],
                        strokeColors: ['#C046D3','#1EA7C5','#FF9432'],
                        border:0,
                        colors:['#C046D3','#1EA7C5','#fff'],
                        hover: {
                            size: 10,
                        }
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec' ],
                        labels: {
                            style: {
                                colors: '#3E4954',
                                fontSize: '14px',
                                fontFamily: 'Poppins',
                                fontWeight: 100,
                            },
                        },
                    },
                    yaxis: {
                        labels: {
                            offsetX:-16,
                            style: {
                                colors: '#3E4954',
                                fontSize: '14px',
                                fontFamily: 'Poppins',
                                fontWeight: 100,
                            },
                        },
                    },
                    fill: {
                        colors:['#C046D3','#1EA7C5','#FF9432'],
                        type:'solid',
                        opacity: 0
                    },
                    colors:['#C046D3','#1EA7C5','#FF9432'],
                    grid: {
                        borderColor: '#f1f1f1',
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 575,
                        options: {
                            markers: {
                                size: [6,6,4],
                                hover: {
                                    size: 7,
                                }
                            }
                        }
                     }]
                };
                
                var chartArea = new ApexCharts(document.querySelector("#chartBar"), optionsArea);
                    chartArea.render();
                }  

                /* Function ============ */
                return {
                    init:function(){},
                    load:function(){
                        chartBar();
                    },
                    resize:function(){}
                }
            }();
            
            jQuery(window).on('load',function(){
                setTimeout(function(){
                    dzChartlist.load();
                }, 1000);       
            });

            jQuery(document).ready(function(){});
            jQuery(window).on('resize',function(){});     
        })(jQuery);
    </script>
@endsection