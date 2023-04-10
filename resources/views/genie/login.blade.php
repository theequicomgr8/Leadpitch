@extends('layouts.app')
@section('title')
     Login  
@endsection
@section('content')

<div class="container">
    <div class="row">
	<div class="login-name">
	  
		<img src="{{URL::asset('assets/images/design.png')}}" style="width: 340px;">
		 
		 </div>
	</div>
	<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="login-heading color-text"><i class="fa fa-lock" style="font-size:16px;color:#FFD700;   margin-right:3px;"></i>Login Box</div>
                <div class="panel-body">
					
					@if(count($errors)>0 && $errors->has('generic_err'))
						<div class="row">
						<div class="col-md-6 col-md-offset-4">
						<div class="alert alert-danger">{{ $errors->first('generic_err') }}</div>
						</div>
					</div>
					@endif
					 
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/genie') }}">
                        {{ csrf_field() }}
						<input type="hidden" name="lgn" value="1" />
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label color-text">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter Email Address">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label color-text">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" placeholder="*******">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label class="color-text">
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Submit
                                </button>
 
                                <a class="color-text" href="{{ url('/genie/reset') }}">Forgot Your Password?</a>
								 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
