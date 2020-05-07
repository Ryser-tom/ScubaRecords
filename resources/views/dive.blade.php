<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->

@extends('layouts.app')

@section('content')
<style>
	</style>
	@php
		xdebug_break();
		$dive = $data[0];

		$date = preg_split('/[-T]/',  $dive["datetime_start"]);

		date_default_timezone_set('Europe/Paris');
		// --- La setlocale() fonctionnne pour strftime mais pas pour DateTime->format()
		setlocale(LC_TIME, 'fr_FR.utf8','fra');// OK
		// strftime("jourEnLettres jour moisEnLettres annee") de la date courante
		$humanDate = strftime("%A %d %B %Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
	@endphp
    <div class="container">
		<div class="row">
			<div class="number col-md-6 text-center">
				<p>{{$humanDate}}</p>
				<p>Plongée: {{$dive["diveNb"]}}</p>
			</div>
			<div class="sive_site_infos col-md-6">
				<table class='table borderless'>

				</table>
			</div>
		</div>
		<div class="row graph_dive"></div>
		<div class="row">
			<div class="tank_infos col-md-6"></div>
			<div class="notes col-md-6"></div>
		</div>
		<div class="row Media"></div>
	</div>
@endsection