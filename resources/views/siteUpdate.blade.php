<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->
<!-- TODO: correct the bootstrap grid -->
@extends('layouts.app')

@section('content')
	@php
		xdebug_break();

	@endphp
        <div class="container">
            <div class="row">
                <div id="map"></div>
            </div>
            <div class="row">
            <form action="/sendUpdateSite" method="post">
				{{csrf_field()}}
                {{ Form::hidden('idSite', $data["idSite"] ?? '')}}
                {{ Form::hidden('lat', $data["latitude"])}}
                {{ Form::hidden('lng', $data["longitude"])}}
                <fieldset>
                    <!-- Form Name -->
                    <legend>Information du site</legend>
                    <div class="form-group row">
                        <label for="name" class="col-2 col-form-label">Nom</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data["name"] ?? ""}}'  id="name" name="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-2 col-form-label">Description</label>
                        <div class="col-10">
                            <textarea class="form-control" id="description" name="description" rows="5">{{$data["description"] ?? ""}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="difficulty" class="col-2 col-form-label">Difficulté</label>
                        <div class="col-10">
                        <input type="number" value='{{$data["difficulty"] ?? ""}}' min="0" max="10" step="1" name="difficulty" id="difficulty"/>
                        </div>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

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
    var lat = {{$data["latitude"]}};
    var lng = {{$data["longitude"]}};
    var map = L.map('map', {zoomControl:true, maxZoom:18, minZoom:5}).setView([lat, lng], 13);
        var basemap =  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributor',    
                        //other attributes.
        }).addTo(map);
        basemap.addTo(map);

        var marker = L.marker([lat, lng]).addTo(map);
    </script>
@endsection