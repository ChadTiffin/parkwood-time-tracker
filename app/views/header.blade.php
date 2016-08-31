<header>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="wrapper">
			<img src="{{url()}}/images/logo.png" class="logo">
			<div class="title">Time Tracker
				<div class="greeting">
					@if (Auth::check())

						Hello, {{Auth::user()->first_name}} {{Auth::user()->last_name}}

					@endif
				</div>
			</div>

			<ul class="nav navbar-nav pull-right">
				
				@if (Auth::check())

				{{ Form::open(array("method" => "post", "url" => action('HomeController@punchClock'), "id" => "punch-clock")) }}

					<input type='submit' value='Clock {{$header_data['clock_direction']}}' class="btn {{$header_data['clock_btn_type']}}">

				{{ Form::close() }}

				<li><a href="{{url()}}/">Summary</a></li>
				<li><a href="{{url()}}/logs/{{ Auth::user()->id }}/{{date('Y')}}-01-01/{{date('Y-m-d')}}">Logs</a></li>
				<li><a href="{{url()}}/settings">Settings</a></li>
				<li><a href="{{url()}}/logout">Logout</a></li>
				@endif
			</ul>
			@if (Auth::check())
			<p class="clock-status">{{$header_data['status']}}</p>
			@endif
		</div>
	</nav>

</header>