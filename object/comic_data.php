<?php
	class comic_data
	{
		private $conn;
		private $table_name = "comic_data";

		public $comic_id;
		public $comic_name;
	
		function __construct($db)
		{
			$this->conn = $db;
		}

		//get comic name by existing comic id
		public function getComic() {
			$query = "SELECT comic_name FROM {$this->table_name} 
						WHERE comic_id = ? 
						LIMIT 1";

			$stmt = $this->conn->prepare($query);

			//sanitize
			$this->comic_id = htmlspecialchars(strip_tags($this->comic_id));

			$stmt->bindParam(1, $this->comic_id, PDO::PARAM_INT);

			if($stmt->execute()) {
				$result = $stmt->fetch();
				$this->comic_name = $result['comic_name'];
				return true;
			}
			else {
				$this->showError($stmt);
				return false;
			}
			
		}

		public function getAllComics() {
			$stmt = $this->conn->prepare("SELECT * FROM comic_data ORDER BY comic_name ASC");
			$stmt->execute();
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			return $stmt->fetchAll();
		}

		public function addComic() {
			$query = "INSERT INTO {$this->table_name} 
						SET comic_name= :comic_name";

			$stmt = $this->conn->prepare($query);

			//sanitize
			$this->comic_name = htmlspecialchars(strip_tags($this->comic_name));

			//bind it
			$stmt->bindParam(":comic_name", $this->comic_name);
			
			if($stmt->execute()) {
				return true;
			}
			else {
				$this->showError($stmt);
				return false;
			}

		}

		public function deleteComic() {
			$query = "DELETE FROM {$this->table_name} 
					WHERE comic_id = ?";

			//prepare
			$stmt = $this->conn->prepare($query);

			//sanitize
			$this->comic_id = htmlspecialchars(strip_tags($this->comic_id));

			//bind
			$stmt->bindParam(1, $this->comic_id, PDO::PARAM_INT);

			//execute
			if($stmt->execute()) {
				return true;
			}
			else {
				$this->showError($stmt);
				return false;
			}

		}

		public function updateComic() {
			$query = "UPDATE {$this->table_name} 
					SET 
						comic_name= :comic_name
					WHERE comic_id = :comic_id";

			//sanitize
			$this->comic_name = htmlspecialchars(strip_tags($this->comic_name));
			$this->comic_id = htmlspecialchars(strip_tags($this->comic_id));

			//prepare
			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(":comic_name", $this->comic_name);
			$stmt->bindParam(":comic_id". $this->comic_id, PDO::PARAM_INT);

			if($stmt->execute()) {
				return true;
			}
			else {
				$this->showError($stmt);
				return false;
			}
		}

		private function showError($stmt) {
		    echo "<pre>";
		        print_r($stmt->errorInfo());
		    echo "</pre>";
		}
	}


?>