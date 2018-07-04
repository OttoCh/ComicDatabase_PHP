<?php
	$post_error = "";
	//how to combination of 2 column unique, in this case comic_id and chapter_number
	//ALTER TABLE chapter_data ADD CONSTRAINT unique_chapter UNIQUE unique_chapter_UQ(chapter_number, comic_id);
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$configs = include('../db_info.php');
		include('../datval.php');

		$servername = $configs['servername'];
		$dbname = $configs['dbname'];
		$username = $configs['username'];
		$password = $configs['password'];

		$comic_id = 1;	//int only, impossible bigger than 1000
		$chapter_number = 1;	//int only, impossible bigger than 1500
		$chapter_name = "name";	//string, no special character, always 0-70 character

		$data_validation = new Data_Sanitization;
		if(empty($_REQUEST['id'])) {
			$post_error .= " No id";
		}
		else {
			$data = $data_validation->data_test($_REQUEST['id']);
			if($data_validation->number_range($data, 0, 1000) == false) {
				$post_error .= " invalid id";
			}
			else {
				$comic_id = $data;
			}
		}

		if(empty($_REQUEST['number'])) {
			$post_error .= " No chapter number";
		}
		else {
			$data = $data_validation->data_test($_REQUEST['number']);
			if(!($data_validation->number_range($data, 0, 1500))) {
				$post_error .= " invalid id";
			}
			else {
				$chapter_number = $data;
			}
		}

		if(empty($_REQUEST['name'])) {
			$post_error .= " No chapter name";
		}
		else {
			$data = $data_validation->data_test($_REQUEST['name']);
			if($data_validation->string_length($data, 70) == true) {
				$chapter_name = $data;
			}
			else {
				$post_error .= " invalid chapter name";
			}
		}

		if($post_error != "") {
			// echo $post_error;
			http_response_code(400);
			die($post_error);
		}


		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

			$query = "INSERT chapter_data(chapter_number, chapter_name, comic_id) VALUES (" . $chapter_number . ",'" . $chapter_name . "'," . $comic_id . ") ON DUPLICATE KEY UPDATE chapter_name = VALUES(chapter_name)";

			$conn->beginTransaction();
			$conn->exec($query);
			$conn->commit();
		}
		catch(PDOException $e) {
			echo ("Connection failed: " . $e->getMessage());
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Comic Chapter
	</title>
	<script type="text/javascript" src="../js/AddChapter.js?v=5"></script>
	<link rel="stylesheet" href="../css/navbar.css">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


</head>
<body onload="retrieveAllChapters()">
	<div class="container-fluid">
		<ul>
			<li><a href="/api/Comic/Index.php">Home</a></li>
			<li><a href="/api/Comic/Pages/Add.php">Add Pages</a></li>
			<li><a href="/api/Comic/All.php">Comic List</a></li>
			<li><a href="/api/Comic/Add.php">Add Comic</a></li>	
		</ul>
	</div>

	<div class="container">
		<div class="page-header">
			<h1>Chapter List</h1>
			<p>Existing chapter</p>
		</div>
	</div>
	
	<div class="container">
		<table id='chapter-table' class="table table-bordered table-striped" align="center">
			<tr>
				<th style="text-align: center;">
					Chapter Number
				</th>
				<th style="text-align: center;">
					Chapter Name
				</th>
			</tr>
		</table>
	</div>
	
	<div class="container">
		<br>
		Chapter Number:
		<input type="number" name="chapter_number" id="input-chapter-number">
		Chapter Name:
		<input type="text" name="chapter_name" id="input-chapter-name">
		<button onclick="addChapter()" onkeypress="">Add</button>
		<br>
	</div>
	<br>
	<div class="container">
		<span id="add-error" class="alert alert-warning" style="visibility: hidden;"></span>
	</div>
</body>
</html>
