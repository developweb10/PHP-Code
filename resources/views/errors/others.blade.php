<html>
	<head>
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 72px;
				margin-bottom: 40px;
			}
			.error-div{
				WIDTH: 85%;
				word-break: break-all;
				margin: 0 auto;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title"><img src="{{ asset('images/metercar.png') }}" style="max-height:100px;" > <br /> <div class="error-div" style="font-size:30px;">{{ $message }}</div> <br /> <a href="{{ URL::previous() }}" >Go back</a> and try again or <a href="{{ URL::to('/contact-us') }}" >Contact Us</a></div>
			</div>
		</div>
	</body>
</html>
