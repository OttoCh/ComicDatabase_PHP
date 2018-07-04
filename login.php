<!DOCTYPE html>
<html>
<head>
	<title>
		Login Form
	</title>
</head>
<body>
	<?php
		include_once "config/core.php";

		$page_title = "Login";

		$require_login = false;
		include_once "User/login_checker.php";

		$access_denied = false;

		//post code
		if($_POST) {
			//username check
			include_once 'config/database.php';
			include_once 'object/user.php';

			$database = new Database();
			$db = $database->getConnection();

			//init object
			$user = new User($db);
			$user->username = $_POST['username'];

			$username_exists = $user->usernameExist();

			//login validation
			if($username_exists && password_verify($_POST['password'], $user->password) && $user->status == 1) {
				//if its true, set session to true
				$_SESSION['logged_in'] = true;
				$_SESSION['user_id'] = $user->id;
				$_SESSION['access_level'] = $user->access_level;
				$_SESSION['firstname'] = htmlspecialchars($user->firstname, ENT_QUOTES, 'UTF-8');
				$_SESSION['lastname'] = $user->lastname;

				//redirect
				if($user->access_level == 'Admin') {
					//redirect to admin page
					header("Location: {$home_url}All.php");
				}
				else if($user->access_level == 'Contributor'){
					header("Location: {$home_url}Pages/Add.php");
				}
			}
			else {
				$access_denied = true;
			}

		}

		//alert message
		$action = isset($_GET['action']) ? $_GET['action'] : "";

		if($action == "not_yet_logged_in") {
			echo "<div class='alert alert-info'>
		        <strong>Please login to access that page.</strong>
		    	</div>";
		}

		if($access_denied){
			echo "<div class='alert alert-danger margin-top-40' role='alert'>
        		Access Denied.<br /><br />
        		Your username or password maybe incorrect
    			</div>";
		}

		?>

		<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>' method="post">
				<input type="text" name="username" placeholder="Username" required autofocus>
				<input type="password" name="password" placeholder="Password" required>
				<input type="submit" value="Log in">
		</form>

		<?php

	?>

</body>
</html>

<!-- https://www.codeofaninja.com/2013/03/php-login-script.html -->