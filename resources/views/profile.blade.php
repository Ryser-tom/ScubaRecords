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
    <div class="main main-raised">
		<div class="profile-content">
            <div class="container">
                <div id="images_profile" class="row">
						<img id="img_profile" src="{{ asset('img/default-profile.svg') }}"/>
				</div>
				<div class="row">
					<!-- TODO: correct the binome -->
					<div id="left-infos" class="col-sm-3"> 
						@if(isset($info['phone'])) 
							<p><i class="fas fa-phone" aria-hidden="true"></i>
							{{$info['phone']}}</p>
						@endif
						<p><i class="fas fa-envelope"></i> {{$info['email']}}</p>
						<p><i class="fas fa-award"></i>
							<a>Certifications</a></p>
						<p>Cmas ** | 01/05/2020</p>
						<p><i class="fas fa-user-friends"></i><a>
							@if(isset($info['idUser'])) 
								Binome
							@else
								Membres
							@endif	
						</a></p>
						<div class="row">
							<div class="col">
								<img id="profile1" class="binome" src="{{ asset('img/default-profile.svg') }}"/>
								<p>binome 1</p>
							</div>
							<div class="col">
								<img id="profile2" class="binome" src="{{ asset('img/default-profile.svg') }}"/>
								<p>binome 2</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<img id="profile3" class="binome" src="{{ asset('img/default-profile.svg') }}"/>
								<p>binome 3</p>
							</div>
							<div class="col">
								<img id="profile4" class="binome" src="{{ asset('img/default-profile.svg') }}"/>
								<p>binome 4</p>
							</div>
						</div>
					</div>
					<div class="col-sm-8">
						<div id="top-infos" class="row">
							<h1>{{$info['name']}}</h1>
							<p id="desc">{{$info['smallDesc']}}</p>
						</div>
						<div id="description">
							{{$info['description']}}
						</div>
					</div>
				</div>
            </div>
@endsection