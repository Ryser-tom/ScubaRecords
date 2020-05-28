<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->

@extends('layouts.app')
@section('content')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<style>
		th {
			color: #02a7f0;
		} 
		.highcharts-figure, .highcharts-data-table table {
			min-width: 360px; 
			max-width: 800px;
			margin: 1em auto;
		}

		.highcharts-data-table table {
			font-family: Verdana, sans-serif;
			border-collapse: collapse;
			border: 1px solid #EBEBEB;
			margin: 10px auto;
			text-align: center;
			width: 100%;
			max-width: 500px;
		}
		.highcharts-data-table caption {
			padding: 1em 0;
			font-size: 1.2em;
			color: #555;
		}
		.highcharts-data-table th {
			font-weight: 600;
			padding: 0.5em;
		}
		.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
			padding: 0.5em;
		}
		.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
			background: #f8f8f8;
		}
		.highcharts-data-table tr:hover {
			background: #f1f7ff;
		}
	</style>

	@php
		xdebug_break();
		$dive = $data[0];
	@endphp
    <div class="container">
		<div class="row">
			<div class="number col-md-6 text-center">
				<p>{{$dive["datetime"] ?? ''}}</p>
				<p>Plongée: {{$dive["diveNb"]}}</p>
			</div>
			<div class="sive_site_infos col-md-6">
			<!-- TODO: remove the border in the table -->
				<table class='table borderless'>
					<tbody>
						<tr>
							<th>site de plongée:</th>
							<td>{{$dive["diveSiteName"]}}</td>
							<th>bateau:</th>
							<td>{{$dive["boat"]}}</td>
						</tr>
						<tr>
							<th>durée de la plongée:</th>
							<td>{{gmdate("H:i:s", $dive["diveduration"])}}</td>
							
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row graph_dive">
		<figure class="highcharts-figure">
			<div id="container"></div>
		</figure>
		</div>
		<div class="row">
			<div class="tank_infos col-md-6">
				<table class='table borderless'>
					<tbody>
						<tr>
							<th>Volume: </th>
							@if(is_numeric($dive["tankvolume"]))
								<td>{{$dive["tankvolume"]*1000}}</td>
							@endif
								<td>Volume inconnu</td>
						</tr>
						<tr>
							<th>Pression début: </th>
							<td>{{substr($dive["tankpressurebegin"], 0, 2)}}</td>
						</tr>
						<tr>
							<th>Mix de gaz</th>
							@if(!is_null($dive["o2"]) && $dive["o2"] > 0)
								<th>%o2: </th>
								<td>{{$dive["o2"]*100}}%</td>
							@endif
							@if(!is_null($dive["n2"]) && $dive["n2"] > 0)
								<th>%n2: </th>
								<td>{{$dive["n2"]*100}}%</td>
							@endif
							@if(!is_null($dive["he"]) && $dive["he"] > 0)
								<th>%he: </th>
								<td>{{$dive["he"]*100}}%</td>
							@endif
							@if(!is_null($dive["ar"]) && $dive["ar"] > 0)
								<th>%ar: </th>
								<td>{{$dive["ar"]*100}}%</td>
							@endif
							@if(!is_null($dive["h2"]) && $dive["h2"] > 0)
								<th>%h2: </th>
								<td>{{$dive["h2"]*100}}%</td>
							@endif
						</tr>
						<tr>
							<th>Quantité de lead: </th>
							<td>{{$dive["leadquantity"]}}</td>
						</tr>
						<br/>
						<tr>
							<th>Consommation moyenne: </th>
							<td>{{$dive["breathingconsumptionvolume"]}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="notes col-md-6">
				<textarea readonly style="width: 546px; height: 490px; align-content:left;">
					{{$dive["description"]}}
				</textarea> 
			</div>
		</div>
		<div class="row Media"></div>
		@if(auth()->user()->idUser == $data[0]["diver"])
			<a class="btn btn-info" href='update/{{$dive["idDive"]}}' role="button">Modifier</a>
		@endif
	</div>
	<script  type="text/javascript">
		let csvData = "{{$data[1]}}".split('|'),
		data = [];
	</script>
	<script src="{{ asset('js/diveGraph.js') }}" type="text/javascript"></script>
@endsection