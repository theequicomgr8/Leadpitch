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
                <div class="login-heading color-text">Mobile OTP</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/genie/otp') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
                            <label for="otp" class="col-md-4 control-label color-text">OTP</label>

                            <div class="col-md-6">
                                <input id="otp" type="otp" class="form-control" name="otp" autocomplete="off">
								@if ($errors->has('otp'))
									<span class="help-block">
										<strong>{{ $errors->first('otp') }}</strong>
									</span>
								@endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Secure Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
