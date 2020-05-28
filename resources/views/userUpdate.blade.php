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
            <form action="/sendUpdateUser" method="post">
				{{csrf_field()}}
                <fieldset>
                    <!-- Form Name -->
                    <legend>Information du profile</legend>
                    <div class="form-group row">
                        <label for="name" class="col-2 col-form-label">Nom profile</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data["name"]}}'  id="name" name="name">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="email" class="col-2 col-form-label">Email</label>
                        <div class="col-10">
                            <input class="form-control" type="email" value='{{$data["email"]}}' id="email" name="email"> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-2 col-form-label">Numéro de téléphone</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data["phone"]}}' id="phone" name="phone">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="smallDesc" class="col-2 col-form-label">Motto</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value='{{$data["smallDesc"]}}' id="smallDesc" name="smallDesc">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-2 col-form-label">Description</label>
                        <div class="col-10">
                            <textarea class="form-control" id="description" name="description" rows="5">{{$data["description"]}}</textarea>
                        </div>
                    </div>

                    <legend>modification du mot de passe</legend>
                </fieldset>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
@endsection