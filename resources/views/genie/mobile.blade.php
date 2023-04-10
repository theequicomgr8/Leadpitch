@extends('layouts.app')
@section('title')
     OTP  
@endsection
@section('content')
<div class="container">
    <div class="row">
	<div class="row">
	<div class="login-name">
	  
		<img src="{{URL::asset('assets/images/design.png')}}" style="width: 340px;">
		 
		 </div>
	</div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="login-heading">Mobile OTP</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/genie') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label color-text">Mobile</label>

                            <div class="col-md-6">
					<select id="mobile" class="form-control mobilehide" name="mobile" onchange="mobileval(this);">
									<option value="">-- Select the mobile --</option>
                                    @if($mobile)
									@foreach($mobile as $mobile_v)
					<option value="{{$mobile_v->mobile}}">{{$mobile_v->mobile}}({{$mobile_v->name}})</option>
									@endforeach
								@endif
									 
								</select>
                                
                            </div>
                        </div>
						
						<div class="form-group">
							<div class="text-center col-md-6 col-md-offset-4 color-text">OR</div>
						</div>
						
						<div class="form-group">
							<label for="otp_to_email" class="col-md-4 control-label color-text">Email</label>
                            <div class="col-md-6">
				<select id="otp_to_email" class="form-control emailhide" name="otp_to_email" onchange="emailval(this);">
									<option value="">-- Select the email --</option>
									@if($email)
										@foreach($email as $email_v)
									<option value="{{$email_v->email}}">{{$email_v->email}}</option>
									@endforeach
									@endif
 
								</select>
                            </div>
						</div>
						
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

function mobileval(sel)
{
   var mobile =  (sel.value);
  
   if(mobile !='')
   {	   
	  $('.emailhide').attr("disabled", "disabled"); 
	   
   }else{
	 
	     $('.emailhide').removeAttr("disabled");
   }
}

function emailval(sel)
{
   var mobile =  (sel.value);
 
   if(mobile !='')
   {	   
	  $('.mobilehide').attr("disabled", "disabled"); 
	   
   }else{
	  
	     $('.mobilehide').removeAttr("disabled");
   }
}
</script>
@endsection
