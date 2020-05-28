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
		xdebug_break();

		function compare_datetime($a, $b){
			return strnatcmp(!$a['datetime'], $b['datetime']);
		}
		$totalDives = count($data)+1;
  		uasort($data, 'compare_datetime');
	@endphp
	<div class="container">
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
					@foreach ($data as $dive)
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
					@foreach ($data as $dive)
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

@endsection
@push('custom-scripts')
<script>
	$(document).ready(function() {
    	$('#example').DataTable();
	} );
</script>
@endpush

