<?php
	class page_data {
		private $conn;
		private $table_name = "page_data";

		public $page_id;
		public $page_number;
		public $file_url;

		public $comic_id;
		public $chapter_id;
	

	function __construct($db) {
		$this->conn = $db;
	}

	function getAllPage($chapter_id) {
		$stmt = $this->conn->prepare("SELECT * FROM `page_data` WHERE chapter_id=?");
		$chapter_id = htmlspecialchars(strip_tags($chapter_id));
		$stmt->bindParam(1, $chapter_id, PDO::PARAM_INT);
		if($stmt->execute()) {
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			// $json = json_encode($result);
			return $result;
		}
		return null;
	}

	private function showError($stmt) {
	    echo "<pre>";
	        print_r($stmt->errorInfo());
	    echo "</pre>";
	}

	}

?>