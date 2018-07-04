<?php
	include_once "config/core.php";

	$page_title = "Register";

	include_once "User/login_checker.php";

	include_once 'config/database.php';
	include_once 'object/user.php';
	// include_once 'libs/php/utils.php';

	if($_POST) {

		//get database connection
		$database = new Database();
		$db = $database->getConnection();

		//init object
		$user = new User($db);
		// $utils = new Utils();

		//set user email to detect if it exist
		$user->email = $_POST['email'];
		$user->username = $_POST['username'];

		$invalid = false;

		if($user->usernameExist()) {
			//error sudah ada yg usernamenya sama
			echo "<div class='alert alert-danger'>The username already exist</div>";
			$invalid = true;
		}
		if($user->emailExist()) {
			//error, email sudah ada yg pakai
			echo "<div class='alert alert-danger'>The email already exist</div>";
			$invalid = true;
		}

		if(!$invalid) {
			//no error, do assign new user
			$user->firstname = $_POST['firstname'];
			$user->lastname = $_POST['lastname'];
			$user->password = $_POST['password'];
			$user->contact_number = $_POST['contact_number'];
			$user->address = $_POST['address'];
			$user->access_level = "Contributor";
			$user->status = 1;

			if($user->create()) {
				echo "<div class'alert alert-info'> Success register <a href='{$home_url}login.php'>Please login</a></div>";
				$_POST = array();
			}
			else {
				echo "<div class='alert alert-danger' role='alert'>Unable to register. Please try again.</div>";
			}
		}
	}

?>
<form action='register.php' method="post" id="register">
	<table class="table table-responsive">
		<tr>
			<td class="width-30-percent">Firstname</td>
			<td><input type="text" name="firstname" class="form-control" required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : ""; ?>"></td>
		</tr>

		<tr>
			<td>Lastname</td>
			<td><input type="text" name="lastname" class="form-control" required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : ""; ?>"></td>
		</tr>
		
		<tr>
			<td>Contact Number</td>
			<td><input type="text" name="contact_number" class="form-control" required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : ""; ?>"></td>
		</tr>

		<tr>
			<td>Address</td>
			<td><textarea name="address" class="form-control" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : ""; ?></textarea></td>
		</tr>

		<tr>
			<td>Username</td>
			<td><input type="text" name="username" class="form-control" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ""; ?>"></td>
		</tr>

		<tr>
			<td>Email</td>
			<td><input type="text" name="email" class="form-control" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ""; ?>"></td>
		</tr>

		<tr>
			<td>Password</td>
			<td><input type="password" name="password" class="form-control" required id="passwordInput"></td>
		</tr>

		<tr>
			<td></td>
			<td>
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-plus"></span> Register
				</button>
			</td>
		</tr>
	</table>
</form>

<?php

?>