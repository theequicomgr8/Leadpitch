@extends('layouts.app')
@section('title')
     Reset Password  
@endsection
@section('content')
<div class="container">
    
	<div class="row">
	<div class="login-name">
	 
		<img src="{{URL::asset('assets/images/design.png')}}" style="width: 340px;">
		 
		 </div>
	</div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="login-heading color-text">Reset Password</div>
						@if(Session::has('success')) 	
							<div class="row">
							<div class="col-md-6 col-md-offset-4">
							<div class="alert alert-success">
							<strong>{{Session::get('success')}}.</strong> 
							</div>
							</div>
							</div>
							@endif
							@if(Session::has('failed')) 	
							<div class="row">
							<div class="col-md-6 col-md-offset-4">
							<div class="alert alert-danger">
							<strong>{{Session::get('failed')}}.</strong> 
							</div>
							</div>
							</div>
							@endif
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('reset/password') }}">
                        {{ csrf_field() }}                      

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label color-text">E-Mail Address</label>
 
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="Enter Email">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label color-text">New Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" Placeholder="New Password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label color-text">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="******">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" name="reset" value="resetPass">
                                    <i class="fa fa-btn fa-refresh"></i> Reset Password
                                </button>
								<a class="color-text" href="{{ url('/login') }}">login</a>
                            </div>
                        </div>
						 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
