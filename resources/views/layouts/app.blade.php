<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
	<link rel="shortcut icon" href="{{ URL::asset('/assets/images/favicon.png') }}" type="image/x-icon">
    <!-- Fonts -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	
	<!-- Chosen CSS -->
	<link rel="stylesheet" href="{{ asset('vendors/chosen-js/chosen.css') }}">
	
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
			background-color: #111212 ! important;
        }

        .fa-btn {
            margin-right: 6px;
        }
		
		.nav_top{
			    margin-top: 12px;
		}
		.login-name{
			 color: #fff;
			 margin-left: 400px;
			 margin-bottom: 35px;
		}	
		.login-name p{
			 color: #fff;
			 font-style: Brush;
		}
		.panel-default {
     
			background-color: #454545;
			border-color: #808080 ! important;
		}
		.login-heading {			
			margin-left: 9px;			 
			padding-bottom: 20px;
			margin-top: 15px;
			color: #fff;
		}
		.color-text{
			color:#FFF;
			
		}
		.help-block{
			color:#FF0000 ! important;
		}
    </style>
</head>
<body id="app-layout" >
  <nav class="navbar navbar-static-top nav_top">
 
  
         <div class="container">
		 
            <!--<div class="navbar-header">

               
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                
                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
               
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                </ul>

               
                <ul class="nav navbar-nav navbar-right">
                    
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>-->
 
        </div>
    </nav> 

    @yield('content')

    <!-- JavaScripts -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="{{ asset('vendors/chosen-js/chosen.jquery.js') }}"></script>
	<script>
		$(".chosen-select").chosen();
	</script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
