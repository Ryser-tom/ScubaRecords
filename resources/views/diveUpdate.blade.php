<!--******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
******************************************************************************-->
<!-- TODO: add possibility to add diveSite -->
@extends('layouts.app')

@section('content')
	@php
		xdebug_break();

        if(isset($data[0]["datetime"])){
			$dateTime = preg_split('/[-T]/',  $data[0]["datetime"]);
			
			$time = $dateTime[3];
			$date = $dateTime[0].'-'.$dateTime[1].'-'.$dateTime[2];
		}
        $duration = $data[0]["diveduration"]/60;

	@endphp
	<div class="container">
        <div class="row">
            <form action="/sendUpdateDive" method="post">
				{{csrf_field()}}
				{{ Form::hidden('idDive', $data[0]["idDive"]) }}
                <fieldset>
                    <!-- Form Name -->
                    <legend>Information sur le site de plongée</legend>
                    <div class="form-group row">
                        <label for="site">Site de plongée</label>
                        <select class="form-control" id="site" name="site">
                        @foreach ($data[1] as $site)
                            @if($site["idDiveSite"] == $data[0]["diveSite"])
                                <option value='{{$site["idDiveSite"]}}' selected>{{$site["name"]}}</option>
                            @else
                                <option value='{{$site["idDiveSite"]}}'>{{$site["name"]}}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="boat" class="col-2 col-form-label">Nom du bateau</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data[0]["boat"]}}'  id="boat" name="boat">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="date" class="col-2 col-form-label">Date</label>
                        <div class="col-10">
                            <input class="form-control" type="date" value='{{$date}}' id="date" name="date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="entryTime" class="col-2 col-form-label">Heure entrée dans l'eau</label>
                        <div class="col-10">
                            <input class="form-control" type="time" value='{{$time}}' id="entryTime" name="entryTime">
                        </div>
                    </div>
                    <!-- TODO: upgrade weather -->
                    <div class="form-group row">
                        <label for="weather">Météo</label>
                        <select class="form-control" id="weather" name="weather">
                        <option>Ensoleillé</option>
                        <option>Neigeux</option>
                        <option>Nuageux</option>
                        <option>Brumeux</option>
                        <option>Ensoleillé</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="5" name="description">{{$data[0]["description"]}}</textarea>
                    </div>
                </fieldset>
                <fieldset>
                    <!-- Form Name -->
                    <legend>information sur l'équipement</legend>

                    <div class="form-group row">
                        <label for="computerName" class="col-2 col-form-label">Nom de l'ordinateur de plongée</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data[0]["name"]}}'  id="computerName" name="computerName">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="model" class="col-2 col-form-label">model / marque de l'ordinateur de plongée</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data[0]["model"]}}'  id="model" name="model">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="o2" class="col-2 col-form-label">quantité o2</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["o2"]}}' min="0" max="1" step="0.001" id="o2" name="o2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="n2" class="col-2 col-form-label">quantité n2</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["n2"]}}' min="0" max="1" step="0.001" id="n2" name="n2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="he" class="col-2 col-form-label">quantité he</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["he"]}}' min="0" max="1" step="0.001" id="he" name="he">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ar" class="col-2 col-form-label">quantité ar</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["ar"]}}' min="0" max="1" step="0.001" id="ar" name="ar">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="h2" class="col-2 col-form-label">quantité h2</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["h2"]}}' min="0" max="1" step="0.001" id="h2" name="h2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tpb" class="col-2 col-form-label">Pression avant plongée (bar)</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["tankpressurebegin"]}}' id="tpb" name="tpb">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lead" class="col-2 col-form-label">quantité de Lead</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["leadquantity"]}}' id="lead" name="lead">
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <!-- Form Name -->
                    <legend>Information de la plongée</legend>
                    <div class="form-group row">
                        <label for="duration" class="col-2 col-form-label">durée de la plongée (en minutes)</label>
                        <div class="col-10">
                            <input class="form-control" type="string" value='{{$duration}}' id="duration" name="diveTime"> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="depth" class="col-2 col-form-label">Profondeur max. (m)</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value='{{$data[0]["greatestdepth"]}}' id="depth" name="depth">
                        </div>
                    </div>
                </fieldset>
                <div class="form-check">
                    <label class="public">
                    <input type="checkbox" name="public" 
                    @if($data[0]["public"] == 0)
                        {{'checked'}}
                    @endif
                    >
                    Partager cette plongée ?
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
                </div>
            </div>
        </div>
	</div>

@endsection


