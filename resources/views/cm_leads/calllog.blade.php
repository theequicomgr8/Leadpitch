@extends('layouts.cm_app')
 @section('title')
     Call Log 
@endsection
@section('content')	
@php 
//dd('working');
@endphp
<style>
    #popup {
/*    display: none;*/
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

#popup:target {
    display: block;
}

.popup-content {
    position: relative;
    top: 30%;
    left: 50%;
    width: 300px;
    height: 207px;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
}

.close-buttor {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    color: black;
    text-decoration: none;
}

</style>
<?php 
//dd($results);
?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
	<div class="right_col" role="main">
		<div class="page-title">
			<div class="title_left">
				<h3>Call Log</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<table class="table" id="calllogtable">
			<thead>
				<tr>
					<!--<th>Candidate Name</th>-->
					<th>Candidate Number</th>
					<th>Status</th>
					<th>Call Start Time</th>
					<th>Call End Time</th>
					<th>Call Duration</th>
					<th>Recruiter</th>
					<th>Audio</th>
				</tr>
			</thead>
			<tbody>
			@foreach($results as $result)
			<tr>
				<!--<td>result['candidate_name']</td>-->
				<td>{{$result['candidate_number'] }}</td>
				<td>{{$result['status'] }}</td>
				<td>{{$result['call_start_time'] }}</td>
				<td>{{$result['call_end_time'] }}</td>
				<td>{{$result['call_duration'] }}</td>
				<td>{{$result['recruiter'] }}</td>
				<td>
					<!--<a href="{{$result['recording_download_link'] }}">Download</a>-->
					@if($result['recording_url']!=null)
					<audio controls> 
                      <source src="{{$result['recording_url'] }}" type="audio/mpeg">
                    </audio>
                    @endif
				</td>
			</tr>
			@endforeach

			<!--<tr>-->
			<!--	<td></td><td></td><td></td><td></td><td></td><td></td>-->
			<!--	<td style="float:right;">-->
			<!--		<a href="{{$next}}" class="btn btn-info">Next</a>-->
			<!--	</td>-->
			<!--</tr>-->
			</tbody>
		</table>

		
		
	</div>

<!--<script src="<?php echo asset('/vendors/jquery/dist/jquery.min.js') ?>"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
	$(document).ready( function () {
	    $('#calllogtable').DataTable();
	} );
</script>


@endsection