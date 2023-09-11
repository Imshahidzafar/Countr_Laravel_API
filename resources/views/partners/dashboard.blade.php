@extends('layout.partners.list_master')
@section('content')
	<!--**********************************
        Chat box End
    ***********************************-->
    <style>
        .donut-chart-sale small {
            font-size: 16px;
		    position: absolute;
		    width: 100%;
		    height: 100%;
		    left: 0;
		    display: flex;
		    align-items: center;
		    top: 0;
		    justify-content: center;
		    font-weight: 600;
        }
    </style>
 	
	<div class="content-body">
        <div style="padding-top:20px;" class="container-fluid">
        	<div class="page-titles mb-n5">
                <ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Dashboard</span>
                    @endsection
                </ol>
            </div>
            <!-- row -->

			<div class="row">
				<div class="col-xl col-md-4 col-sm-6">
					<div class="card">
						<div style="padding: 0.25rem !important;" class="card-body p-4">
							<img src="/public/images/total-transactions.svg" alt="image">
							<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(255, 213, 174)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">5/8</span>
							<div style="padding-left: 20px;padding-bottom: 20px;">
								<h2 class="fs-24 text-black font-w600 mb-0">{{$total_survey_categories}}</h2>
								<span class="fs-14">My Surveys Categories</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl col-md-4 col-sm-6">
					<div class="card">
						<div style="padding: 0.25rem !important;" class="card-body p-4">
							<img src="/public/images/total-offers.svg" alt="image">
							<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(238, 252, 255)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">8/8</span>
							<div style="padding-left: 20px;padding-bottom: 20px;">
								<h2 class="fs-24 text-black font-w600 mb-0">{{$total_survey_list}}</h2>
								<span class="fs-14">My Surveys</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl col-md-4 col-md-6">
					<div class="card">
						<div style="padding: 0.25rem !important;" class="card-body p-4">
							<img src="/public/images/total-connects.svg" alt="image">
							<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(238, 252, 255)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">8/8</span>
							<div style="padding-left: 20px;padding-bottom: 20px;">
								<h2 class="fs-24 text-black font-w600 mb-0">{{$total_survey_list_questions}}</h2>
								<span class="fs-14">My Survey Questions</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl col-md-4 col-md-6">
					<div class="card">
						<div style="padding: 0.25rem !important;" class="card-body p-4">
							<img src="/public/images/total-partners.svg" alt="image">
							<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(238, 252, 255)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">8/8</span>
							<div style="padding-left: 20px;padding-bottom: 20px;">
								<h2 class="fs-24 text-black font-w600 mb-0">0</h2>
								<span class="fs-14">Total Attempts on Survey</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--
			<div class="row">
				<div class="col-md-12 col-sm-9">
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
			</div>
			-->
    	</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

	<link href="{{url('public')}}/vendor/chartist/css/chartist.min.css" rel="stylesheet" type="text/css"/>
	<script src="{{url('public')}}/vendor/chart.js/Chart.bundle.min.js" type="text/javascript"></script>
	<script src="{{url('public')}}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
	<script src="{{url('public')}}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		(function($) {
			/* "use strict" */
			var dzChartlist = function(){	
				var screenWidth = $(window).width();
				var chartBar = function(){
					var optionsArea = {
			          	series: [{
			            	name: "Survey Attempts",
			            	data: [20, 40, 20, 80, 40, 40, 20, 60, 60, 20, 110, 60]
			          	}],
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
				        	width: [4],
					  		colors:['#A87B5D'],
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
			          		size: [6],
					  		strokeWidth: [4],
					  		strokeColors: ['#FF9432'],
					  		border:0,
					  		colors:['#fff'],
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
							colors:['#FF9432'],
							type:'solid',
							opacity: 0.2
						},
						colors:['#FF9432'],
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
								chart: {
									height: 250,
								},
								markers: {
								 	size: [4],
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
				
				var pieChart = function(){
					var options = {
				      	series: [20, 30, 60],
				      	chart: {
				      		type: 'donut',
				  			height:200,
				    	},
						legend: {
							show:false,
						},
						fill:{
							colors:['#0F172A','#A87B5D','#EBEBEB']
						},
						stroke:{
							width:0,
						},
						colors:['#0F172A','#A87B5D','#EBEBEB'],
						dataLabels: {
					    	enabled: false
				    	}
				   };
			   
				   var chart = new ApexCharts(document.querySelector("#pieChart"), options);
				   chart.render();
				}
				/* Function ============ */
				return {
					init:function(){},
			
					load:function(){
						chartBar();
						pieChart();
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
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
	<script>
	    $(document).ready(function () {
			$('#example').DataTable();
		});
	</script>    
@endsection