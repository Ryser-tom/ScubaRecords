<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->

@extends('layouts.app')

@section('content')
	@php
		//xdebug_break();
		
		if(isset($data[1]["idDiveSite"])){
			$dives=$data[0];
		}else{
			$dives=$data;
		}

		function compare_datetime($a, $b){
			return strnatcmp(!$a['datetime'], $b['datetime']);
		}
		$totalDives = count($dives)+1;
  		uasort($dives, 'compare_datetime');
	@endphp
	<div class="container">
	@if(isset($data[1]["idDiveSite"]))
		<div class="row">
			<form action="/Site/update" method="post">
				{{csrf_field()}}
				{{ Form::hidden('lat', $data[1]["latitude"])}}
                {{ Form::hidden('lng', $data[1]["longitude"])}}
                {{ Form::hidden('name', $data[1]["name"])}}
                {{ Form::hidden('description', $data[1]["description"])}}
                {{ Form::hidden('difficulty', $data[1]["difficulty"])}}
				<div id="map"></div>
				<button type="submit" class="btn btn-primary">Modifié le site de plongée</button>
			</form>
        </div>
	@endif
		<div class="table-responsive">
		<!-- TODO: find a way to get the real number of the dive -->
			@if(Request::is('*/personnal*'))
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>site de plongée</th>
							<th>date</th>
							<th>durée</th>
							<th>public</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($dives as $dive)
						<tr>
							<th scope="row"><a href="../dive/{{$dive['idDive']}}"> {{$totalDives - $loop->iteration}}</a></th>
							<td>{{$dive["diveSiteName"]}}</td>
							<td>{{$dive["datetime"]}}</td>
							<td>{{gmdate("H:i:s", $dive["diveduration"])}}</td>
							<td>
								@if ($dive["public"] == 0)
									<i class="fas fa-check"></i>
								@else
									<i class="fas fa-times"></i>
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@elseif (Request::is('*/public*') || Request::is('*/followed') )
			<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>plongeur</th>
							<th>site de plongée</th>
							<th>date</th>
							<th>durée</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($dives as $dive)
						<tr>
							<th scope="row"><a href="../dive/{{$dive['idDive']}}"> {{$loop->iteration}}</a></th>
							<td><a href="../../user/{{$dive['username']}}">{{$dive["username"]}}</a></td>
							<td>{{$dive["diveSiteName"]}}</td>
							<td>{{$dive["datetime"]}}</td>
							<td>{{gmdate("H:i:s", $dive["diveduration"])}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@else
				you don't have any records!
			@endif
		</div>
	</div>

	<!-- TODO: place code in external file -->
	@if(isset($data[1]["idDiveSite"]))
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.4/MarkerCluster.Default.css" />
		<link rel="stylesheet" href="style.css" />
		<style>
			#map { height: 350px; }
		</style>

		<!-- Map script -->
		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.4/leaflet.markercluster.js"></script>
		<script>
		var lat = {{$data[1]["latitude"]}};
		var lng = {{$data[1]["longitude"]}};
		var map = L.map('map', {zoomControl:true, maxZoom:18, minZoom:5}).setView([lat, lng], 13);
			var basemap =  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
							attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributor',    
							//other attributes.
			}).addTo(map);
			basemap.addTo(map);

			var marker = L.marker([lat, lng]).addTo(map);
		</script>
	@endif
@endsection


