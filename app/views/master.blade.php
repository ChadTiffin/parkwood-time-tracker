<!DOCTYPE html>
<html>
<head>
	<title>Chad's Laravel Time Tracker</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<link rel="stylesheet" href="<?=url()?>/css/bootstrap-datetimepicker.min.css" />

	<link rel="stylesheet" type="text/css" href="<?=url();?>/css/global.css">
</head>
<body>
<header>

	<nav class="navbar navbar-default navbar-fixed-top">
		<img src="{{url()}}/images/logo.png" class="logo">
		<div class="title">Time Tracker</div>
		<ul class="nav navbar-nav pull-right">
			
			<form method="post" action="<?=url()?>/index.php/punch-clock" id="punch-clock"><input type='submit' value='Clock {{$header_data['clock_direction']}}' class="btn {{$header_data['clock_btn_type']}}"></form>
			<li><a href="{{url()}}/">Summary</a></li>
			<li><a href="{{url()}}/logs/{{date('Y')}}-01-01/{{date('Y-m-d')}}">Logs</a></li>
		</ul>
		<p class="clock-status">{{$header_data['status']}}</p>
	</nav>

</header>

@yield('content')

</div>

<!-- Confirmation Log Modal -->
<div id="confirmation-modal" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="confirm-model">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script type="text/javascript" src="<?=url()?>/js/moment.min.js"></script>
<script type="text/javascript" src="<?=url()?>/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
	var BASE_URL = "<?=url()?>";
</script>
<script type="text/javascript" src="<?=url()?>/js/global.js"></script>

@yield('js')

</body>
</html>