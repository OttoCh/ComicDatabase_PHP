<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$configs = include('db_info.php');
		include('datval.php');

		$servername = $configs['servername'];
		$dbname = $configs['dbname'];
		$username = $configs['username'];
		$password = $configs['password'];

		$error = "";
		$comic_name = "";

		$DataValidation = new Data_Sanitization;
		if(empty($_REQUEST['comic_name'])) {
			$error .= "No comic name";
		}
		else {
			$data = $DataValidation->data_test($_REQUEST['comic_name']);
			// $data = mysql_real_escape_string($data);
			$comic_name = $data;
		}

		if($error != "") {
			echo $error;
			http_response_code(400);
			die($error);
		}

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

			$query = "INSERT INTO `comic_data`(`comic_name`) VALUES (\"" . $comic_name . "\");";
			$stmt = $conn->prepare($query);

			//SELECT comic_id, COUNT('total_chapter') FROM chapter_data GROUP BY comic_id;
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo ("Connection failed: " . $e->getMessage());
			http_response_code(400);
		}
		die();
	}
?>


<!DOCTYPE html>
<html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		Add New Comic
	</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/AddComic.js?v=2"></script>
	<link rel="stylesheet" href="./css/navbar.css">
</head>
<body>
<div class="container-fluid">
	<ul>
		<li><a href="/api/Comic/Index.php">Home</a></li>
		<li><a href="/api/Comic/Pages/Add.php">Add Pages</a></li>
		<li><a href="/api/Comic/All.php">Comic List</a></li>
		<li><a href="/api/Comic/Add.php" class="active">Add Comic</a></li>		
	</ul>
</div>

<div class="container">
	<div class="page-header">
		<h1>Add Comic</h1>
		<p>Add new comic</p>
	</div>
</div>

<div class="container">
	Comic:
	<input type="text" name="comic_name" id="comic-name-input">
	<button id="button-post" class="btn">Add</button>
	<br>
	<span id="add-error" class="alert alert-warning" style="visibility: hidden;"></span>
</div>
</body>
</html>