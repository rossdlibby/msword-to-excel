<html>
	<head>
		<title>.doct &rarr; xcel</title>
		
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
		<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

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
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
			label{ color: #2e2e2e;font-size: 120%; }
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">.doc &nbsp;&nbsp;&minus;>&nbsp;&nbsp; Excel</div>
				{!! Form::open(['post' => '/', 'files' => 'true']) !!}
					<label for="file">Upload Your .doc file <i class="fa fa-file-word-o"></i></label>
					<hr>
					<div class="row text-center">
						<div class="col-md-2 col-md-offset-4">
							{!! Form::file('file', ['id' => 'file']) !!}
						</div>
					</div>
					<hr>
					<p><button type="submit" class="btn btn-success">Upload &amp; convert <i class="fa fa-file-excel-o"></i></button></p>
				{!! Form::close() !!}
			</div>
		</div>
	</body>
</html>
