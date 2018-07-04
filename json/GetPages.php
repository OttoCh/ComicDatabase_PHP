<?php
	include_once "../config/core.php";
	$page_title = "Get Comic List";

	$require_login = false;
	include_once "../User/login_checker.php";

	$access_denied = false;

	include_once "../config/database.php";

	include_once "../object/page_data.php";

	$db = new Database();

	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(empty($_REQUEST['id'])) {
			http_response_code(400);
			die();
		}

		$chapter_id = $_REQUEST['id'];
		$conn = $db->getConnection();
		$pages = new page_data($conn);

		$all_pages = $pages->getAllPage($chapter_id);
		//echo var_dump($all_comic);
		$json = json_encode($all_pages);
		echo $json;
	}
?>
