@extends("layout")
@section("content")
<div>
	<ol class="breadcrumb">
	  <li><a href="{{{URL::route('user.home')}}}">{{ trans('messages.home') }}</a></li>
	  <li class="active"><a href="{{ URL::route('reports.patient.index') }}">{{ Lang::choice('messages.report', 2) }}</a></li>
	  <li class="active">{{ trans('messages.turnaround-time') }}</li>
	</ol>
</div>
<div class="container-fluid" style="margin-bottom: 20px; margin-top: 20px;">
        {{ Form::open(array('route' => array('reports.aggregate.tat'), 'id' => 'turnaround', 'class' => 'form-inline')) }}
            <div class="row">
                <div class="col-md-2">
                    <div class="col-md-2">
                        <label for="date_from">{{ Form::label('start', trans("messages.from")) }}</label>
                    </div>
                    <div class="col-md-10">
                        {{ Form::text('start', isset($input['start'])?$input['start']:date('Y-m-01'), 
					        array('class' => 'form-control standard-datepicker')) }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="col-md-2">
                        <label for="date_to">{{ Form::label('end', trans("messages.to")) }}</label>
                    </div>
                    <div class="col-md-10">
                        {{ Form::text('end', isset($input['end'])?$input['end']:date('Y-m-d'), 
					        array('class' => 'form-control standard-datepicker')) }}
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="col-md-5">
                        <label for="test_category">{{ Form::label('description',  Lang::choice('messages.test-category', 2)) }}</label>
                    </div>
                    <div class="col-md-7">
                        {{ Form::select('section_id', array(''=>trans('messages.select-lab-section'))+$labSections, 
							    		Request::old('testCategory') ? Request::old('testCategory') : $testCategory, 
											array('class' => 'form-control', 'id' => 'section_id')) }}
                    </div>
                </div>
                
               <div class="col-md-2">
                    <div class="col-md-5">
                        <label for="test_status">{{ Form::label('description', Lang::choice('messages.test-type', 1)) }}</label>
                    </div>
                    <div class="col-md-7">
                        {{ Form::select('test_type', array('' => trans('messages.select-test-type'))+$testTypes, 
							    		Request::old('testType') ? Request::old('testType') : $testType, 
											array('class' => 'form-control', 'id' => 'test_type')) }}
                    </div>
                </div>
                <div class="col-md-2">
                </div>
                <div class="col-md-1">
                        {{ Form::button("<span class='glyphicon glyphicon-filter'></span> ".trans('messages.view'), 
			        array('class' => 'btn btn-info', 'id' => 'filter', 'type' => 'submit')) }}
                </div>
            </div>
        {{ Form::close() }}
    </div>

<div class="panel panel-primary">
	<div class="panel-heading ">
		<span class="glyphicon glyphicon-user"></span>
		{{ trans('messages.turnaround-time') }}
	</div>
	<div class="panel-body">
		<!-- if there are filter errors, they will show here -->
		@if ($error)
			<div class="alert alert-info">{{ $error }}</div>
		@else
		<div id = "{{$charts_id}}" >

			<?php $continue = 1; ?>
			@if(count($series_data)==0)
				<?php $continue = 0; ?>
				<table class="table-responsive">
					<tr>
						<td>{{ trans("messages.no-records-found") }}</td>
					</tr>
				</table>
			@endif
		</div>
		@endif
	</div>
</div>
<!-- Begin HighCharts scripts -->
{{ HTML::script('highcharts/highcharts.js') }}
{{ HTML::script('highcharts/exporting.js') }}
<!-- End HighCharts scripts -->

	<script language = "JavaScript">
         $(document).ready(function() {
            var title = {
               text: <?php echo '"'.trans("messages.turnaround-time").'"'; ?>   
            };
            var subtitle = {
               text:  <?php 
		        			$subtitle = '';
		        			$from = isset($input['start'])?$input['start']:date('d-m-Y');
							$to = isset($input['end'])?$input['end']:date('d-m-Y');
							if($from!=$to)
								$subtitle = trans("messages.from").' '.$from.' '.trans("messages.to").' '.$to;
							else
								$subtitle = trans("messages.for").' '.date('Y');
							if($interval=='M')
								$subtitle.= ' ('.trans("messages.monthly").') ';
							else if($interval=='D')
								$subtitle.= ' ('.trans("messages.daily").') ';
							else 
								$subtitle.= ' ('.trans("messages.weekly").') ';

							if($testCategory)
								$subtitle.= ' - '.TestCategory::find($testCategory)->name;
							if($testType)
								$subtitle.= '('.TestType::find($testType)->name.')';
							echo '"'.$subtitle.'"';
						?>
            };
            var xAxis = {
               categories: <?php echo json_encode($date_array)?>
            };
            var yAxis = {
               title: {
                  text: 'Average taken (<?php echo $tat_units ?>)'
               },
               plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
               }]
            };   

            var tooltip = {
               valueSuffix: '<?php echo " ".$tat_units ?>'
            }
            var legend = {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0
            };
            var series =  <?php echo json_encode($series_data)?>

            var json = {};
            json.title = title;
            json.subtitle = subtitle;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            json.legend = legend;
            json.series = series;
            //display charts only if there are records

            $('#container').highcharts(json);
         });
      </script>
@stop