<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Registration</title>
	</head>	

	<body>
		<?php
			//Connect to database, start session, and check that https protocol is being used
			require_once('dbconnect.php');
			require_once('httpscheck.php');
			session_start();
			
			//Get the user input and add it to the database
			if(isset($_POST['submit'])){
				
				$userCheck = htmlspecialchars($_POST['username']);
				
				pg_prepare($conn, "lookup", 'SELECT * FROM lab8.authentication WHERE username = $1');
				$userNameCheck = pg_execute($conn, "lookup", array($userCheck));

				//Check to see if the username is taken
				if (pg_num_rows($userNameCheck) < 1){
					$username = htmlspecialchars($_POST['username']);
					//hash and salt the user's password
					mt_rand();
					$salt = sha1(mt_rand());
					$password_hash = sha1($salt . htmlspecialchars($_POST['password']));

					$user_info = "INSERT INTO lab8.user_info(username) VALUES ($1) ";
					$authentication = "INSERT INTO lab8.authentication(username, password_hash, salt) VALUES($1, $2, $3)";

					pg_prepare($conn, "user_info", $user_info);
					pg_prepare($conn, "authentication", $authentication);

					pg_execute($conn, "user_info", array($username));
					pg_execute($conn, "authentication", array($username, $password_hash, $salt));

					$_SESSION['username'] = $username;
					$ip_address = $_SERVER["REMOTE_ADDR"];
					$action = 'register';

					$query = 'INSERT INTO lab8.log(username, ip_address, action) VALUES($1, $2, $3)';
					pg_prepare($conn, "log", $query);
					pg_execute($conn, "log", array($username, $ip_address, $action));
				}

				else{
					echo "<p style='color: red;'>Username is taken. Please enter a new username.</p>";
				}
			}

			if(isset($_SESSION['username'])){
				header('Location: ./home.php');
			}


		?>

		<div class="container" align="center">
			<p>Please register</p>
			<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" name="form">
				<div class="username">
					<label for="user">Username</label>
					<input type="text" id="user" placeholder="Username" name="username" required><span id="feedback">  </span>
					
				</div>
				<div class="password">
					<label for="pass">Password</label>
					<input type="password" name="password" id="pass" placeholder="Password" required>
				</div>
				<div id="buttons">
					<span><button type="submit" name="submit" style="width:150px;" class="btn btn-success">Create account</button></span>
					<span><button type="button" style="width:150px;" class="btn btn-danger" onclick=" top.location.href='index.php'">Cancel</button></span>
				</div>
			</form>
		</div>
	</body>
</html>	