<?php
		class chapter_data {
			private $conn;
			private $table_name;

			public function __construct($db) {
				$this->conn = $db;
			}

			public $comic_id;
			public $chapter_id;
			public $chapter_number;
			public $chapter_name;

			public function addChapter() {

			}

			public function getChapter() {

			}

			public function deleteChapter() {

			}

			public function getAllChapters($comic_id) {
				$stmt = $this->conn->prepare("SELECT chapter_id, chapter_number, chapter_name FROM chapter_data WHERE comic_id=? ORDER BY chapter_number ASC");
				$stmt->bindParam(1, $comic_id, PDO::PARAM_INT);
				$stmt->execute();

				$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
				$r = $stmt->fetchAll();
				return $r;
			}

			private function showError($stmt) {
			    echo "<pre>";
			        print_r($stmt->errorInfo());
			    echo "</pre>";
			}
		}
?>