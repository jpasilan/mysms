<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>MySMS</title>

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    {{ HTML::style('//checkout.stripe.com/v3/checkout/button.css') }}
    
    {{ HTML::style('assets/css/main.css') }}
	</head>
	<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">MySMS</a>
        </div>
        @if (Sentry::check())
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Sentry::getUser()->email }} <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li>{{ HTML::link('logout', 'Logout')}}</li>
              </ul>
            </li>
          </ul>
        </div>
        @endif
      </div>
    </nav>
    <div class="container">
      @if (Session::has('message'))
      <?php
        $class = key(Session::get('message'));
        $message = Session::get('message')[$class];
      ?>
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-{{ $class }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ $message }}
          </div>
        </div>
      </div>
      @endif

			@yield('content')
		</div>

		<script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    {{ HTML::script('assets/js/jquery.textchange.min.js') }}
    {{ HTMl::script('assets/js/main.js') }}
    
    @yield('more-footer-scripts')
	</body>
</html>