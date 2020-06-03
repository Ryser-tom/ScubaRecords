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
		//xdebug_break();
	@endphp
	<div class="container">
		<form action="/uploadDive" method="post" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="custom-dive">
				<input type="file" class="custom-file-input" id="log" name="log">
				<label class="custom-file-label" for="file">Choose file</label>
			</div>
			<div class="form-check">
				<input type="checkbox" class="form-check-input" id="exampleCheck1" name="public" checked>
				<label class="form-check-label" for="exampleCheck1">public ?</label>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>

@endsection
@push('custom-scripts')
<script>
	$(document).ready(function() {
    	$('#example').DataTable();
	} );
</script>
@endpush

