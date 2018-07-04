<?php
	include_once "../config/core.php";
	$page_title = "Get Comic List";

	$require_login = false;
	include_once "../User/login_checker.php";

	$access_denied = false;

	include_once "../config/database.php";

	include_once "../object/comic_data.php";

	$db = new Database();

	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		$conn = $db->getConnection();
		$comic = new comic_data($conn);

		$all_comic = $comic->getAllComics();
		//echo var_dump($all_comic);
		$json = json_encode($all_comic);
		echo $json;
	}


?>