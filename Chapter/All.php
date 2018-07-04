<?php 
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		$comic_id = 1;
		//get the parameter comic_id
		if(empty($_REQUEST["id"])) {
			//return blank json
			http_response_code(400);
			die("blank");
		}
		else {
			include('../datval.php');
			$data_validation = new Data_Sanitization;
			$data = $data_validation->data_test($_REQUEST["id"]);
			if((is_numeric($data) ? 'true' : 'false') == true) {
				$comic_id = $data;
			}
			else {
				http_response_code(400);
				die("blank");
			}
		}

		$configs = include('../db_info.php');
		
		$servername = $configs['servername'];
		$dbname = $configs['dbname'];
		$username = $configs['username'];
		$password = $configs['password'];
		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

			$stmt = $conn->prepare("SELECT chapter_id, chapter_number, chapter_name FROM chapter_data WHERE comic_id=" . $comic_id . " ORDER BY chapter_number ASC");
			$stmt->execute();

			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$r = $stmt->fetchAll();
			$json_data = json_encode($r);
			echo $json_data;
			// foreach ($r as $key => $value) {
			// 	echo $value['chapter_id'] . " " . $value['chapter_number'] . " " . $value['chapter_name'];
			// }
		}
		catch(PDOException $e) {
				echo ("Connection failed: " . $e->getMessage());
			}
	}
?>