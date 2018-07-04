<?php
	include_once "../config/core.php";
	$page_title = "Login";

	$require_login = true;
	include_once "../User/login_checker.php";

	$access_denied = false;

	include_once "../config/database.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Add new comic and chapter
	</title>
		<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="../js/AddPages.js?v=7"></script>
	<link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
<div class="container-fluid">
	<ul>
		<li><a href="/api/Comic/Index.php">Home</a></li>
		<li><a href="/api/Comic/Pages/Add.php" class="active">Add Pages</a></li>
		<li><a href="/api/Comic/All.php">Comic List</a></li>
		<li><a href="/api/Comic/Add.php">Add Comic</a></li>	
	</ul>
</div>
<div class="container">
	<div class="page-header">
		<h1>Add Pages</h1>
		<p>Add pages to selected comic and pages</p>
	</div>

	<div class="row">
		<div class="col-md-6">
			<span>Comic   :</span>
			<select onchange="retrieveAllChapters()" id="comic-select">
			<?php 
					class createComicDropdown{

						function createOption($comid_id, $comic_name) {
							return "\n<option value=" . $comid_id . ">" . $comic_name . "</option>"; 
						}

						function __construct($comic_info) {
							// echo "<select onchange=\"retrieveAllChapters()\" id=\"comic-select\">";
							echo "<option value=0></option>";
							foreach ($comic_info as $key => $value) {
								echo $this->createOption($value['comic_id'], $value['comic_name']);
							}
							// echo "\n</select>";
						}


					}

					$database = new Database();
					$db = $database->getConnection();

					include_once "../object/comic_data.php";

					$comic_data = new comic_data($db);
					$c = new createComicDropdown($comic_data->getAllComics());

					// $servername = "localhost";
					// $username = "root";
					// $password = "langsung";
					// $dbname = "comicdb";

					// // if($_SERVER['REQUEST_METHOD'] == 'GET') {
					// 	// echo "Get request <br>";
					// 	try {
					// 		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
					// 		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

					// 		$stmt = $conn->prepare("SELECT * FROM comic_data");
					// 		$stmt->execute();

					// 		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

					// 		$c = new createComicDropdown($stmt->fetchAll());

					// 		// foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
					// 	 //        echo $v . "iterator";
					// 	 //    }
					// 	}
					// 	catch(PDOException $e) {
					// 		echo ("Connection failed: " . $e->getMessage());
					// 	}

					// 	$conn = null;
					// }
			?>
			</select>
		</div>
		<div class="col-md-6">
			<span>Chapters:</span>
			<select id="chapter-select">
			</select>
		</div>
	</div>
<br><br>
<table id="page-input-table" class="table table-bordered table-striped">
	<tr>
		<th>
			Page Number 
		</th>
		<th>
			File URL
		</th>
	</tr>
	<tr id="page-input">
		<td class="input-row">
			<input type="number" name="page_number[]" class="page-number-input" style="width: 100%;">
		</td>
		<td class="input-row">
			<input type="text" name="page_url[]" class="page-url-input" style="width: 100%;">
		</td>
	</tr>
</table>

<br>
<button onclick="addNewPagesInput()" class="btn">Add</button>
<button onclick="getAllAnswer()" class="btn">Submit</button>

<?php 
		$post_error = "";
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$error = "";
			// $comic_id = $_REQUEST["comic_id"];
			// $chapter_id = $_REQUEST["chapter_id"];
			// $page_number = $_REQUEST["pages"];
			// $page_url = $_REQUEST["url"];

			$comic_id = 1;
			$chapter_id = 1;
			$page_number = 1;
			$page_url = "url";
			
			include '../datval.php';
			$data_validation = new Data_Sanitization;
			if(empty($_REQUEST["comic_id"])) {
				$error .= "no comic id";
			}
			else {
				$data = $data_validation->data_test($_REQUEST["comic_id"]);
				if((is_numeric($data) ? 'true' : 'false') == true) {
					$comic_id = $data;
				}
				else {
					$error .= "invalid comic id";
				}
			}

			if(empty($_REQUEST["chapter_id"])) {
				$error .= "no chapter id";
			}
			else {
				$data = $data_validation->data_test($_REQUEST["chapter_id"]);
				if((is_numeric($data) ? 'true' : 'false') == true) {
					$chapter_id = $data;
				}
				else {
					$error .= "invalid chapter id";
				}
			}

			if(empty($_REQUEST["pages"])) {
				$error .= "no pages";
			}
			else {
				$data = $data_validation->data_test($_REQUEST["pages"]);
				$page_number = $data;
				$pages = explode(',', $data);
				for($i=0; $i < count($pages); $i++) {
					if((is_numeric($pages[$i]) ? 'true' : 'false') == false) {
						$error .= "Invalid number pages";
						break;
					}
				}
			}

			if(empty($_REQUEST["url"])) {
				$error .= "no url";
			}
			else {
				$data = $data_validation->data_test($_REQUEST["url"]);
				$page_url = $data;
				$url = explode(',', $data);
				for($i=0; $i < count($url); $i++) {
					if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url[$i])) {
		      			$error .= "Invalid URL";
		      			break; 
		    		}
				}
			}

			
			if($error != "") {
				http_response_code(400);
				die($error);
			}

			$pages = explode(',', $page_number);
			$url = explode(',', $page_url);

			// echo $comic_id . " " . $chapter_id;
			// echo var_dump($page_number);
			// echo var_dump($page_url);

			try {
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

				//example query
				//INSERT INTO `page_data`(`page_number`, `file_length`, `file_url`, `chapter_id`, `comic_id`) 
				// VALUES (1,280,'bc',1,1),
				// (2,280,'c',1,1)
				// ON DUPLICATE KEY UPDATE file_url = VALUES(file_url)

				$query = "INSERT INTO page_data(page_number, file_length, file_url, chapter_id, comic_id) VALUES";

				for($i=0; $i < count($pages); $i++) {
					$q = "(" . $pages[$i] . "," . "280" . ",\"" . $url[$i] . "\"," . $chapter_id . "," . $comic_id . ")";
					if($i != count($url) - 1) {
						$q = $q . ",";
					}
					$query = $query . $q;
				}

				$onDuplicateQuery = " ON DUPLICATE KEY UPDATE file_url = VALUES(file_url)";
				$query .= $onDuplicateQuery;

				// echo $query;
				$conn->beginTransaction();
				$conn->exec($query);
				$conn->commit();

				// echo "Added new record";

			}
			catch(PDOException $e) {
				echo ("Connection failed: " . $e->getMessage());
				$post_error = "Something wrong, please try again!";
			}

			$conn = null;
		}
?>
<br><br>
<span id="notification" class="alert alert-warning" style="visibility:hidden;"><?php echo $post_error ?></span>
</div>
</body>
</html>