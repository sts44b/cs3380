<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Update</title>
	</head>	

	<body>
		<?php
			//continue session and connect to the database
			session_start();
			require_once('dbconnect.php');
			require_once('httpscheck.php');

			//if something is not right with the session go the the login page
			if (!isset($_SESSION['username'])) {
				header('Location: ./index.php');
			}

			//if the user saves the updated description then update the database and log
			if (isset($_POST['submit'])) {
				$username = $_SESSION['username'];
				$description = $_POST['description'];

				$query = 'UPDATE lab8.user_info SET description = $1 WHERE username = $2';
				pg_prepare($conn, "description", $query);
				pg_execute($conn, "description", array($description, $username));
				header('Location: ./home.php');

				$ip_address = $_SERVER["REMOTE_ADDR"];
				$action = 'Updated description';
				$query = 'INSERT INTO lab8.log(username, ip_address, action) VALUES($1, $2, $3)';
				pg_prepare($conn, "log", $query);
				pg_execute($conn, "log", array($username, $ip_address, $action));
			}


		?>

		<!--div to display the input area for the 
		description and options to save or logout-->
		<div class="container" align="center">
			<form action="<?=$_SERVER['PHP_SELF'] ?>" method="post">
				<p>Username: <?=$_SESSION['username']?></p>
				<table border="1">
					<tbody>
						<tr>
							<td>
								<strong>Description</strong>
							</td>	
							<td>
								<input type="text" name="description"></input>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="submit" name="submit" value="Save">
				<p><a href="logout.php">Click here to logout</a></p>			
			</form>	
		</div>
	</body>
</html>