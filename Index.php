<!DOCTYPE html>
<html>
<head>
	<title>
		Comic Admin Panel
	</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/index.css"></link>
	<link rel="stylesheet" href="./css/navbar.css">
</head>
<body>
<div class="container-fluid">
	<ul>
		<li><a href="/api/Comic/Index.php" class="active">Home</a></li>
		<li><a href="/api/Comic/Pages/Add.php">Add Pages</a></li>
		<li><a href="/api/Comic/All.php">Comic List</a></li>
	</ul>
</div>
<div class="container">
	<div class="page-header">
		<h1>Comic Admin Panel</h1>
		<p>Welcome! </p>
	</div>
</div>
<div class="container">
	<ul class="list-group">
		<li class="list-group-item" style="float: none;">
			<h3>List of current comic </h3>
			<a href="All.php">Comic Index</a>
		</li>
		<li class="list-group-item" style="float: none;">
			<h3>Add new chapter to existing comic </h3>
			<a href="Chapter/Add.php">Add Chapter</a>
		</li>
		<li class="list-group-item" style="float: none;">
			<h3>Add pages to existing chapter</h3>
			<a href="Pages/Add.php">Add Pages</a>
		</li>
	</ul>
</div>

</body>
</html>