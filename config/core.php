<?php
	//activate all error reporting
	error_reporting(E_ALL);

	//start php session
	session_start();

	//set default time-zone
	date_default_timezone_set("Asia/Jakarta");

	//home page url
	$home_url = "http://localhost/api/Comic/";

	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	$records_per_page = 5;

	$from_record_num = ($records_per_page * $page) - $records_per_page;  

?>