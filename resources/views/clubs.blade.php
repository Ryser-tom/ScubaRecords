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
		#images_profile{
			height:150px;
			position: relative;
			background-image: url(../img/default-banner.jpg);
		}
		#profile_img{
			height: 80px; 
			position: absolute; 
			bottom: 0;
		}
		#img_profile{
			height: 80px; 
			position: absolute; 
			bottom: 0;
		}
		.binome{
			height: 50px;
			position: relative;
		}
		#left-infos{
			margin-top: 60px;
		}
		#profile1{
			float: left;
		}
		#profile2{
			float: right;
		}
		#profile3{
			float: left;
		}
		#profile4{
			float: right;
		}
		.row{
			margin-bottom: 30px;;
		}
		#desc{
			font-weight: bold;
		}
		h1{
			margin-top: 0px;
		}
		.description{
			background-color: lightgray;
		}
	</style>
	@php
		xdebug_break();
	@endphp
	<div class="container">
		<div class="table-responsive">
			@if(Request::is('*/all'))
				<table class="table">
					<tbody>
						<tr>
							<th scope="row" rowspan="2">LOGO</th>
							<td>Nom du club</td>
							<td rowspan="2">nombres de membres</td>
						</tr>
						<tr>
							<td>motto</td>
						</tr>
					</tbody>
				</table>
			@elseif (Request::is('*'))
			<table class="table">
					<thead>
						<tr>
							<th>nom du club</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($data as $club)
						<tr>
							<td><a href="../club/{{$club['idClub']}}">{{$club["name"]}}</a></td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@else
				something went wrong !
			@endif
		</div>
	</div>

@endsection
@push('custom-scripts')
<script>
	$(document).ready(function() {
    	$('#example').DataTable();
	} );
</script>
@endpush

