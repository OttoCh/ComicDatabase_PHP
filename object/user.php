<?php
	class User{
		// database connection and table name
		private $conn;
		private $table_name = "users";

		// object properties
		public $id;
		public $firstname;
		public $lastname;
		public $username;
		public $email;
		public $contact_number;
		public $address;
		public $password;
		public $access_level;
		public $status;
		public $modified;
		public $created;

		//constuctor
		public function __construct($db) {
			$this->conn = $db;
		}

		public function usernameExist() {
			$query = "SELECT id, firstname, lastname, access_level, password, status
						FROM " . $this->table_name . "
						WHERE username = ?
						LIMIT 0,1";

			//prepare the query
			$stmt = $this->conn->prepare($query);

			//sanitize
			$this->username=htmlspecialchars(strip_tags($this->username));

			//bind param
			$stmt->bindParam(1, $this->username);

			//execute the query
			$stmt->execute();

			$num = $stmt->rowCount();

			if($num > 0) {
				//get record details/values
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				//assign values to object properties
				$this->id = $row['id'];
		        $this->firstname = $row['firstname'];
		        $this->lastname = $row['lastname'];
		        $this->access_level = $row['access_level'];
		        $this->password = $row['password'];
		        $this->status = $row['status'];

		        //return tru
		        return true;
			}

			return false;
		}

		public function emailExist() {
			$query = "SELECT id, firstname, lastname, access_level, password, status
						FROM " . $this->table_name . "
						WHERE email = ?
						LIMIT 0,1";

			//prepare the query
			$stmt = $this->conn->prepare($query);

			//sanitize
			$this->email=htmlspecialchars(strip_tags($this->email));

			//bind param
			$stmt->bindParam(1, $this->email);

			//execute the query
			$stmt->execute();

			$num = $stmt->rowCount();

			if($num > 0) {
				//get record details/values
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				//assign values to object properties
				$this->id = $row['id'];
		        $this->firstname = $row['firstname'];
		        $this->lastname = $row['lastname'];
		        $this->access_level = $row['access_level'];
		        $this->password = $row['password'];
		        $this->status = $row['status'];

		        //return tru
		        return true;
			}

			return false;
		}

		public function create() {
			$this->created=date('Y-m-d H:i:s');

			$query = "INSERT INTO {$this->table_name} 
						SET 
							firstname = :firstname,
			                lastname = :lastname,
			                username = :username,
			                email = :email,
			                contact_number = :contact_number,
			                address = :address,
			                password = :password,
			                access_level = :access_level,
			                status = :status,
			                created = :created";

			$stmt = $this->conn->prepare($query);

			//sanitize
			$this->firstname = htmlspecialchars(strip_tags($this->firstname));
			$this->lastname = htmlspecialchars(strip_tags($this->lastname));
			$this->email = htmlspecialchars(strip_tags($this->email));
			$this->username = htmlspecialchars(strip_tags($this->username));
			$this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
			$this->address = htmlspecialchars(strip_tags($this->address));
			$this->password = htmlspecialchars(strip_tags($this->password));
			$this->access_level = htmlspecialchars(strip_tags($this->access_level));
			$this->status = htmlspecialchars(strip_tags($this->status));

			//bind values
			$stmt->bindParam(':firstname', $this->firstname);
			$stmt->bindParam(':lastname', $this->lastname);
			$stmt->bindParam(':username', $this->username);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':contact_number', $this->contact_number);
			$stmt->bindParam(':address', $this->address);
			//hash password before bind
			$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
			$stmt->bindParam(':password', $password_hash);

			$stmt->bindParam(':access_level', $this->access_level);
			$stmt->bindParam(':status', $this->status);
			$stmt->bindParam(':created', $this->created);

			if($stmt->execute()) {
				return true;
			}
			else {
				$this->showError($stmt);
				return false;
			}

		}

		public function showError($stmt){
		    echo "<pre>";
		        print_r($stmt->errorInfo());
		    echo "</pre>";
		}
	}
?>