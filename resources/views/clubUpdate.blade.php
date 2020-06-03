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
		//xdebug_break();

	@endphp
    @if(!isset($data[0]["endDateTime"]))
        <div class="container">
            <div class="row">
                <form action="/sendUpdateClub" method="post">
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Information sur le Club de plongée</legend>
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Nom</label>
                                <div class="col-5">
                                    <input class="form-control" type="text" value='{{$data[0]["name"] ?? ''}}'  id="name" name="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="motto" class="col-2 col-form-label">Motto</label>
                                <div class="col-5">
                                    <input class="form-control" type="text" value='{{$data[0]["smallDesc"] ?? ''}}'  id="motto" name="motto">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" rows="5" name="description">{{$data[0]["description"] ?? ''}}</textarea>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-2 col-form-label">Email</label>
                                <div class="col-5">
                                    <input class="form-control" type="email" value='{{$data[0]["email"] ?? ''}}'  id="email" name="email">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                    @if(isset($data))
                        <fieldset>
                            <!-- TODO: make the member gestion -->
                            <legend>Gestion des membres</legend>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>nom</th>
                                        <th>type</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($data[1] as $member)
                                    <tr>
                                        <td>{{$member["name"]}}</td>
                                        <td>
                                        @if ($data[0]["master"] == $member["idUser"])
                                            <td>
                                                <i class="fas fa-crown"></i>
                                            </td>
                                        @else
                                            <td>
                                                <i class="fas fa-user"></i>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirm">
                                                    <i class="fas fa-crown"></i>
                                                </button>
                                            </td>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </fieldset>
                        @endif
                    </div>
                    <div class="col-md-12">
                        {{csrf_field()}}
                        {{ Form::hidden('master', $data[0]["master"] ?? Auth::user()->idUser) }}
                        {{ Form::hidden('clubName', $data[0]["name"])}}
                        @if(isset($data))
                            {{ Form::hidden('idClub', $data[0]["idClub"])}}
                        @else
                            {{ Form::hidden('idClub', "")}}
                        @endif
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ url('/club/').'/'.$data[0]['idClub'] }}" type="button" class="btn btn-danger">annuler</a>
                            <!-- TODO: modal for final check -->
                            <a href="{{ url('/closeClub/').'/'.$data[0]['idClub'] }}" class="btn btn-warning">Fermer le groupe</a>
                    </div>
                </form>
            </div>
        </div>
    @else
        <h1> Ce club as été fermé le {{$data[0]["endDateTime"]}} </h1>
    @endif
    <!-- Modal -->
@endsection
@section('myjsfile')
    <script>
        $('#confirm').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    </script>
@endsection


