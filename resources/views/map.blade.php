<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->

@extends('layouts.app')

@section('content')
<div class="card">
    <div id="map"></div>
</div>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.4/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
        #map { height: 850px; }
    </style>


    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.3/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.4/leaflet.markercluster.js"></script>
    <script>
        var data = <?php echo $data ?>;
        var map = L.map('map', {zoomControl:true, maxZoom:18, minZoom:2}).fitWorld();
        var basemap =  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributor',    
                        //other attributes.
        }).addTo(map);
        basemap.addTo(map);
        function popUp(feature, layer) {
            var text = '<h3>' + feature.properties['name'] + '</h3>' + 
                '<a href="../dives/personnal/'+feature.properties['name']+'">Listes de mes plongées sur le site</a><br />' + 
                '<a href="../dives/public/'+feature.properties['name']+'">Listes des plongées public sur le site</a>';
            layer.bindPopup(text);
        }
        
        //insert data on map
        var points = new L.geoJson(data,{
            onEachFeature: popUp
        });
        var markers = L.markerClusterGroup({spiderfyOnMaxZoom: false});
        markers.addLayer(points).addTo(map);
        function openPopUp(id, clusterId){
            map.closePopup(); //which will close all popups
            map.eachLayer(function(layer){     //iterate over map layer
                if (layer._leaflet_id == clusterId){         // if layer is markerCluster
                    layer.spiderfy(); //spiederfies our cluster
                }
            });
            map.eachLayer(function(layer){     //iterate over map rather than clusters
                if (layer._leaflet_id == id){         // if layer is marker
                    layer.openPopup();
                }
            });
        }
        markers.on('clusterclick', function(a){
            if(a.layer._zoom == 18){
                popUpText = '<ul>';
                //there are many markers inside "a". to be exact: a.layer._childCount much ;-)
                //let's work with the data:
                for (feat in a.layer._markers){
                }
                popUpText += '</ul>';
                //as we have the content, we should add the popup to the map add the coordinate that is inherent in the cluster:
                var popup = L.popup().setLatLng([a.layer._cLatLng.lat, a.layer._cLatLng.lng]).setContent(popUpText).openOn(map); 
            }
        })
    </script>
@endsection