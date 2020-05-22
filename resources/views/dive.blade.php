<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->

@extends('layouts.app')
@push('head')
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/data.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<script src="{{ asset('js/chart.js')}}"></script>

	<!-- Additional files for the Highslide popup effect -->
	<script src="https://www.highcharts.com/media/com_demo/js/highslide-full.min.js"></script>
	<script src="https://www.highcharts.com/media/com_demo/js/highslide.config.js" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="https://www.highcharts.com/media/com_demo/css/highslide.css" />
@endpush
@section('content')
<style>
	</style>
	@php
		xdebug_break();
		$dive = $data[0];
		$date = "";
		if(isset($dive["datetime_start"])){
			$date = preg_split('/[-T]/',  $dive["datetime_start"]);
			
			date_default_timezone_set('Europe/Paris');
			// --- La setlocale() fonctionnne pour strftime mais pas pour DateTime->format()
			setlocale(LC_TIME, 'fr_FR.utf8','fra');// OK
			// strftime("jourEnLettres jour moisEnLettres annee") de la date courante
			$humanDate = strftime("%A %d %B %Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
		}

	@endphp
    <div class="container">
		<div class="row">
			<div class="number col-md-6 text-center">
				<p>{{$humanDate ?? ''}}</p>
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
							<th>date:</th>
							<td>{{$dive["datetime"]}}</td>
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

			</div>
			<div class="notes col-md-6">

			</div>
		</div>
		<div class="row Media"></div>
		@if(auth()->user()->idUser == $data[0]["diver"])
			<a class="btn btn-info" href='update/{{$dive["idDive"]}}' role="button">Modifier</a>
		@endif
	</div>
@endsection