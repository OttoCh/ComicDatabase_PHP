<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		All Comic
	</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./css/navbar.css">
</head>
<body>
<div class="container-fluid">
	<ul>
		<li><a href="/api/Comic/Index.php">Home</a></li>
		<li><a href="/api/Comic/Pages/Add.php">Add Pages</a></li>
		<li><a href="/api/Comic/All.php" class="active">Comic List</a></li>
		<li><a href="/api/Comic/Add.php">Add Comic</a></li>	
	</ul>
</div>
<div class="container">
	<div class="page-header">
		<h1>All Comic List</h1>
		<p>List of all comic that exist in database</p>
	</div>
</div>

<div class="container">
<table id="comic-table" class="table table-striped table-bordered">
	<tr>
		<th id="comic-number-table-header" class="col-lg-1">
			No.
		</th>
		<th id="comic-name-table-header" class="col-lg-5">
			Comic Name
		</th>
		<th id="chapter-number-table-header" class="col-lg-3">
			Total Chapter
		</th>
		<th id="edit-chapter-table-header" class="col-lg-3">
		</th>
	</tr>
	<?php
		class createComicTable {
			function __construct($comic_info) {
				$index = 1;
				// echo var_dump($comic_info);
				foreach($comic_info as $key => $value) {
					echo $this->createRow($index, $value['comic_name'], $value['COUNT(chapter_data.chapter_name)'], $value['comic_id']);
					$index += 1;
				}
			}

			function createRow($no, $comic_name, $total_chapter, $comic_id) {
				return "<tr><td>" . $no . "</td><td>" . $comic_name . "</td><td>" .$total_chapter . "</td><td><a href=\"/api/Comic/Chapter/Add.php?id=" . $comic_id . "\">Chapter List</a></td</tr>";
			}
		}


		$configs = include('db_info.php');
		
		$servername = $configs['servername'];
		$dbname = $configs['dbname'];
		$username = $configs['username'];
		$password = $configs['password'];
		
		try {
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

				$stmt = $conn->prepare("SELECT comic_data.comic_id, comic_data.comic_name, COUNT(chapter_data.chapter_name) FROM comic_data LEFT JOIN chapter_data ON comic_data.comic_id = chapter_data.comic_id GROUP BY comic_data.comic_id ORDER BY comic_data.comic_name ASC;");

				//SELECT comic_id, COUNT('total_chapter') FROM chapter_data GROUP BY comic_id;
				$stmt->execute();

				$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

				$c = new createComicTable($stmt->fetchAll());

			}
			catch(PDOException $e) {
				echo ("Connection failed: " . $e->getMessage());
			}
		
	?>
</table>
</div>

</body>
</html>