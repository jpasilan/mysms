@extends('layouts.master')

@section('content')
<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<h2>MySMS Login</h2>
		<div class="row">
			<div class="col-md-12">
				{{ Form::open(['url' => URL::to('login')]) }}
					<div class="form-group">
						{{ Form::email('email', '', 
              ['id' => 'email', 'placeholder' => 'Email', 'class' => 'form-control input-lg', 'required' => true]) }}
					</div>
					<div class="form-group">
    					{{ Form::password('password', ['id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control input-lg']) }}
  					</div>
  					{{ Form::submit('Sign In', ['class' => 'btn btn-lg btn-primary btn-block']) }}
  				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
@stop