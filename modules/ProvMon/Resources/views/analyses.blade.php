@extends ('provmon::split')

@section('content_dash')
	<div class="d-flex flex-wrap justify-content-between" style="min-height: 135px;">
	<div class="d-flex justify-content-end align-self-start {{ ($dash && count($dash) == 1) ? 'order-1 order-sm-3' : 'order-3'}}" style="flex: 1">
			@include('Generic.documentation', ['documentation' => $modem->help])
		</div>
		@if ($dash)
		<div class="{{ count($dash) == 1 ? 'col-sm-10 col-xl-11 order-2' : '' }} ">
			@foreach ($dash as $key => $info)
				<div class="alert alert-{{$info['bsclass']}} fade show">
					<div>
						{{ $info['text'] }}
					</div>
					@if (isset($info['instructions']))
						<div class="m-t-10 m-b-5">
							<code class="p-5">{{ $info['instructions'] }}</code>
						</div>
					@endif
				</div>
			@endforeach
		</div>
		@else
			<b>TODO</b>
		@endif
	</div>
@stop

@section('spectrum-analysis')
	@include('provmon::spectrum-analysis')
@stop

@section('content_cacti')

	@if ($host_id)
		<iframe id="cacti-diagram" src="/cacti/graph_view.php?action=preview&columns=1&host_id={{$host_id}}" sandbox="allow-scripts allow-same-origin" onload="resizeIframe(this);" scrolling="no" style="width: 100%;"></iframe>
	@else
		<font color="red">{{trans('messages.modem_no_diag')}}</font><br>
		{{ trans('messages.modem_monitoring_error') }}
	@endif
	@include('provmon::cacti-height')
@stop

@section('content_ping')
	<div class="tab-content">
		<div class="tab-pane fade in" id="ping-test">
			@if ($online)
				<font color="green"><b>Modem is Online</b></font><br>
			@else
				<font color="red">{{trans('messages.modem_offline')}}</font>
			@endif
			{{-- pings are appended dynamically here by javascript --}}
		</div>

		<div class="tab-pane fade in" id="flood-ping">
					<form method="POST">Type:
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<select class="select2 form-control m-b-20" name="flood_ping" style="width: 100%;">
							<option value="1">low load: 500 packets of 56 Byte</option> {{-- needs approximately 5 sec --}}
							<option value="2">average load: 1000 packets of 736 Byte</option> {{-- needs approximately 10 sec --}}
							<option value="3">big load: 2500 packets of 56 Byte</option> {{-- needs approximately 30 sec --}}
							<option value="4">huge load: 2500 packets of 1472 Byte</option> {{-- needs approximately 30 sec --}}
						</select>

				{{-- Form::open(['route' => ['ProvMon.flood_ping', $view_var->id]]) --}}
				@if (isset($flood_ping))
					@foreach ($flood_ping as $line)
							<table class="m-t-20">
							<tr>
								<td>
									 <font color="grey">{{$line}}</font>
								</td>
							</tr>
							</table>
					@endforeach
				@endif
					<div class="text-center">
						<button class="btn btn-primary m-t-10" type="submit">Send Ping</button>
					</div>
					</form>
		</div>
	</div>
@stop

@section('content_log')
<div class="tab-content">
	<div class="tab-pane fade in" id="log">
		@if ($log)
			<font color="green"><b>Modem Logfile</b></font><br>
			@foreach ($log as $line)
				<table>
					<tr>
						<td>
						 <font color="grey">{{$line}}</font>
						</td>
					</tr>
				</table>
			@endforeach
		@else
			<font color="red">{{ trans('messages.modem_log_error') }}</font>
		@endif
	</div>
	<div class="tab-pane fade in" id="lease">
		@if ($lease)
			<font color="{{$lease['state']}}"><b>{{$lease['forecast']}}</b></font><br>
			@foreach ($lease['text'] as $line)
				<table>
					<tr>
						<td>
							<font color="grey">{!!$line!!}</font>
						</td>
					</tr>
				</table>
			@endforeach
		@else
			<font color="red">{{ trans('messages.modem_lease_error')}}</font>
		@endif
	</div>
	<div class="tab-pane fade in" id="configfile">
		@if ($configfile)
			@if ($device != 'tr069')
				<font color="green"><b>Modem Configfile ({{$configfile['mtime']}})</b></font><br>
				@if (isset($configfile['warn']))
					<font color="red"><b>{{$configfile['warn']}}</b></font><br>
				@endif
			@endif
			@foreach ($configfile['text'] as $line)
				<table>
					<tr>
						<td>
						 <font color="grey">{{$line}}</font>
						</td>
					</tr>
				</table>
			@endforeach
		@else
			<font color="red">{{ trans('messages.modem_configfile_error')}}</font>
		@endif
	</div>

	<div class="tab-pane fade in" id="eventlog">
		@if ($eventlog)
			<div class="table-responsive">
				<table class="table streamtable table-bordered" width="100%">
					<thead>
						<tr class='active'>
							<th width="20px"></th>
							@foreach (array_shift($eventlog) as $col_name)
								<th class='text-center'>{{$col_name}}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
					@foreach ($eventlog as $row)
						<tr class = "{{$row[2]}}">
							<td></td>
							@foreach ($row as $idx => $data)
								@if($idx != 2)
									<td><font>{{$data}}</font></td>
								@endif
							@endforeach
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		@else
			<font color="red">{{ trans('messages.modem_eventlog_error')}}</font>
		@endif
	</div>

	<div class="tab-pane fade in" id="preeq">
		@if ($preeq)
			<font color="green"><b>Pre-Equalization Data</b></font><br>
			<div class="table-responsive">
				<table class="=table table-bordered" width="100%">
					<thead>
						<tr>
							<th class="text-center">Tap</th>
							<th class="text-center">Energy (db)</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">
								@for ($i = 1; $i < 25; $i++)
									<font color="black">{{$i}}<br></font>
								@endfor
							</td>
							<td class="text-center">
								@foreach ($preeq['energy'] as $line)
									<font color="black">{{$line}}<br></font>
								@endforeach
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="container-fluid box" style="width:100%; overflow: hidden;"">
				<font color="black"><b>TDR = </b><font color="blue">{{$preeq['tdr']}}</font><font color="black"><b> meters</b></font></font>
			</div>
			<div width="500px" height="400px" style="z-index: 9999;">
				<canvas id="myChart"></canvas>
			</div>

			@section('mycharts')
			<script>
			window.onload = (function(_event)
			{
				setTimeout(function(){
					console.log(_event);
					var ctx = document.getElementById("myChart").getContext('2d');
					console.log('Hi John');
					var js_array = [<?php echo '"'.implode('","', $preeq['energy']).'"' ?>];
					var myChart = new Chart(ctx,
					{
						type: 'bar',
						data: {
							{{-- labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"], --}}
							labels: Array.apply(null, {length: 25}).map(Number.call, Number),
							datasets: [{
								label: 'Tap Energy Distribution',
								{{-- data: [,12, 19, 3, 5, 2, 3], --}}
								data: js_array,
								backgroundColor: [
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 99, 132, 0.2)',
								'rgba(54, 162, 235, 0.2)',
								'rgba(255, 206, 86, 0.2)',
								'rgba(75, 192, 192, 0.2)',
								'rgba(153, 102, 255, 0.2)',
								'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
								'rgba(255,99,132,1)',
								'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(75, 192, 192, 1)',
								'rgba(153, 102, 255, 1)',
								'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});
				}, 1000);
			});
			</script>
			@stop


		@else
			<font color="red">{{ trans('messages.preeq_error') }}</font>
		@endif
	</div>
</div>

@stop

@if (Module::collections()->has('HfcCustomer'))
	@section('content_proximity_search')

		{!! Form::open(array('route' => 'CustomerTopo.show_prox', 'method' => 'GET')) !!}
		{!! Form::label('radius', 'Radius / m', ['class' => 'col-md-2 control-label']) !!}
		{!! Form::hidden('id', $modem->id) !!}
		{!! Form::number('radius', '1000') !!}
		<input type="submit" value="Search...">
		{!! Form::close() !!}

	@stop
@endif


@section('content_realtime')
	@if ($realtime)
		<font color="green"><b>{{$realtime['forecast']}}</b></font><br>
		@foreach ($realtime['measure'] as $tablename => $table)
		<h4>{{preg_replace('/^DT_/', '', $tablename)}}</h4>
			@if (Str::startsWith($tablename, 'DT_'))
			<div class="table-responsive">
				<table class="table streamtable table-bordered" width="auto">
					<thead>
						<tr class="active">
							<th/>
							@foreach ($table as $colheader => $colarray)
								<th class="text-center">{{$colheader}}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach(current($table) as $i => $dummy)
						<tr>
							<td width="20"/>
							@foreach ($table as $colheader => $colarray)
								@if (is_array($colarray[$i]))
								<td class="text-center {{$colarray[$i][1]}}"><font color="grey">{{$colarray[$i][0]}}</font></td>
								@else
								<td class="text-center"><font color="grey">{{$colarray[$i]}}</font></td>
								@endif
							@endforeach
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<table class="table" style="width: auto;">
			@foreach ($table as $rowname => $row)
				<tr>
					<th width="15%">
						{{$rowname}}
					</th>
					@foreach ($row as $linename => $line)
						<td>
							<font color="grey">{{htmlspecialchars($line)}}</font>
						</td>
					@endforeach
				</tr>
			@endforeach
				<div style="float: right;">
					@if ($tablename == array_keys($realtime['measure'])[0])
						@if ($picture == 'images/modems/default.webp')
							<a href="https://github.com/nmsprime/nmsprime/issues/882">
								<img style="max-height: 150px; max-width: 200px; margin-top: 50px; display: block;" src="{{ url($picture) }}"></img>
							</a>
							<i style="float: right;" class="fa fa-2x p-t-5 fa-question-circle text-info" title="{{ trans('messages.contribute_modem_picture') }}"></i>
							<p style="color:red;">{{ trans('messages.no_modem_picture') }}</p>
						@else
							<img style="max-height: 150px; max-width: 200px; margin-top: 50px; display: block;" src="{{ url($picture) }}"></img>
						@endif
					@endif
				</div>
			</table>
			@endif
		@endforeach
	@else
		<font color="red">{{trans('messages.modem_offline')}}</font>
		@if ($picture == 'images/modems/default.webp')
			<div style="text-align: center">
				<a href="https://github.com/nmsprime/nmsprime/issues/882" style="vertical-align: middle;">
					<img style="max-height: 300px; max-width: 300px; margin: auto; display: inline;" src="{{ url($picture) }}"></img>
				</a>
			</div>
			<i style="float: right;" class="fa fa-2x p-t-5 fa-question-circle text-info" title="{{ trans('messages.contribute_modem_picture') }}"></i>
			<p style="color:red; margin-left: auto; margin-right: auto;">{{ trans('messages.no_modem_picture') }}</p>
		@else
			<img style="max-height: 300px; max-width: 300px; margin: auto; display: block;" src="{{ url($picture) }}"></img>
		@endif
	@endif
@stop

@section ('javascript')

<script type="text/javascript">

@if ($ip)

	$(document).ready(function() {

		setTimeout(function() {

			var source = new EventSource(" {{ route('ProvMon.realtime_ping', $ip) }}");

			source.onmessage = function(e) {
				// close connection
				if (e.data == 'finished')
				{
					source.close();
					return;
				}

				document.getElementById('ping-test').innerHTML += e.data;
			}

		}, 500);
	});
@endif
</script>

<script language="javascript">
	let targetPage = window.location.href;
		targetPage = targetPage.split('?');
		targetPage = targetPage[0];
	let panelPositionData = localStorage.getItem(targetPage) ? localStorage.getItem(targetPage) : localStorage.getItem("{!! isset($view_header) ? $view_header : 'undefined'!!}");

    let event = 'load';
	if (panelPositionData)
		event = 'localstorage-position-loaded';

	$(window).on(event, function() {
		$(document).ready(function() {
			$('table.streamtable').DataTable({
			{{-- Translate Datatables Base --}}
				@include('datatables.lang')
			responsive: {
				details: {
					type: 'column' {{-- auto resize the Table to fit the viewing device --}}
				}
			},
			autoWidth: false,
			paging: false,
			info: false,
			searching: false,
			aoColumnDefs: [ {
	            className: 'control',
	            orderable: false,
	            searchable: false,
	            targets:   [0]
			} ]
			});
		});
	});
</script>
@include('Generic.handlePanel')
@yield('spectrum')
@stop
