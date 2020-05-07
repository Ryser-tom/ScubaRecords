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
			@if(Request::is('*/personnal'))
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>localité</th>
							<th>site de plongée</th>
							<th>bateau</th>
							<th>date</th>
							<th>profondeur max</th>
							<th>durée</th>
							<th>public</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($data as $dive)
						<tr>
							<th scope="row"><a href="../dive/{{$dive['idDive']}}"> {{$loop->iteration}}</a></th>
							<td>{{$dive["locationName"]}}</td>
							<td>{{$dive["diveSiteName"]}}</td>
							<td>{{$dive["boat"]}}</td>
							<td>{{$dive["datetime_start"]}}</td>
							<td></td>
							<td>{{gmdate("H:i:s", $dive["dive_duration"])}}</td>
							<td>
								@if ($dive["public"] == 1)
									<i class="fas fa-check"></i>
								@else
									<i class="fas fa-times"></i>
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@elseif (Request::is('*/public'))
			<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>plongeur</th>
							<th>localité</th>
							<th>site de plongée</th>
							<th>bateau</th>
							<th>date</th>
							<th>profondeur max</th>
							<th>durée</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($data as $dive)
						<tr>
							<th scope="row"><a href="../dive/{{$dive['idDive']}}"> {{$loop->iteration}}</a></th>
							<td><a href="../user/{{$dive['username']}}">{{$dive["username"]}}</a></td>
							<td>{{$dive["locationName"]}}</td>
							<td>{{$dive["diveSiteName"]}}</td>
							<td>{{$dive["boat"]}}</td>
							<td>{{$dive["datetime_start"]}}</td>
							<td></td>
							<td>{{gmdate("H:i:s", $dive["dive_duration"])}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@else
				I don't have any records!
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

