@extends('layouts.app')
@section('title')
     Login  
@endsection
@section('content')

<div class="container">
    <div class="row">
	<div class="login-name">
	  
		<img src="{{URL::asset('/assets/images/design.png')}}" style="width: 340px;">
		 
		 </div>
	</div>
	<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <!--<div class="login-heading color-text"><i class="fa fa-lock" style="font-size:16px;color:#FFD700;   margin-right:3px;"></i>Company id</div>-->
                <div class="panel-body">
				 <div class="row">
                  <div class="col-md-6 col-md-offset-4">
					@if(count($errors)>0 && $errors->has('generic_err'))
						  
						<div class="alert alert-danger">{{ $errors->first('generic_err') }}</div>
						 
					@endif
				</div>
				</div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
						<input type="hidden" name="login" value="2" />
                        <div class="form-group">
                            <label for="company_id" class="col-md-4 control-label color-text">Company Id</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="company_id" value="{{ old('company_id') }}" placeholder="Company Id" required>

                                @if ($errors->has('company_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                     Submit
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
