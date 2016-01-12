<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS 3380 Lab 8</title>
	</head>	

	<body>
		<?php
			//Connect to database, start session, and check that https protocol is being used
			require_once('httpscheck.php');
			session_start();
			require_once('dbconnect.php');

			//look up the user's login information
			if (isset($_POST['submit'])) {
				$username = htmlspecialchars($_POST['username']);
				pg_prepare($conn, "lookup", 'SELECT salt, password_hash FROM lab8.authentication WHERE username = $1');
				$result = pg_execute($conn, "lookup", array($username));

				$row = pg_fetch_assoc($result);
				$localhash = sha1($row['salt'] . htmlspecialchars($_POST['password']));

				//check to see that the correct user login information was entered and add the login to the log
				if ($localhash == $row['password_hash']){
					$_SESSION['username'] = $username;
					$ip_address = $_SERVER["REMOTE_ADDR"];
					$action = 'login';

					$query = 'INSERT INTO lab8.log(username, ip_address, action) VALUES($1, $2, $3)';
					pg_prepare($conn, "log", $query);
					pg_execute($conn, "log", array($username, $ip_address, $action));
				}
				else{
					//prompt the user if the incorrect information was entered
					echo "<p style='color: red;'>Incorrect username/password combination</p>";
				}
			}

			//if the correct user information was entered go to home page
			if(isset($_SESSION['username'])){

				header('Location: ./home.php');
			}

		?>

		<!--Div to display the input boxes, submit button and registration link-->
		<div align="center">
			<div id="login">
				<p>Please login</p>
				<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
					<label for="username">username:</label>
					<input type="text" name="username" id="username" placeholder="username">
					<label for="password">password:</label>
					<input type="password" name="password" id="password" placeholder="password">
					<br>
					<input type="submit" name="submit" value="submit"></input>
				</form>
				<p>Register <a href="registration.php">here</a></p>
			</div>
		</div>	
	</body>	
</html>