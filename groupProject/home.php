<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Home</title>
		<style>
			body{
				text-align: center;
				margin-left: auto;
			}
		</style>
	</head>
	<body>
		<?php
			//Connect to database and continue session
			require_once('dbconnect.php');
			session_start();
			
			// Get the user's description
			$description = "SELECT correct_picks, wrong_picks FROM group19.user_info WHERE username = $1";
			pg_prepare($conn, "description", $description);
			$description = pg_execute($conn, "description", array($_SESSION['username']));
			$description = pg_fetch_array($description, null, PGSQL_ASSOC);

		?>
		<!--Div to display the user's information-->
		<div class="container" align="center">
			<p>Username: <?=$_SESSION['username']?></p>
			<p>User Record: <?=$description['description']?></p>
			
			<!--User options to update their description or logout-->
			<p><a href="update.php">Click</a> to update page.</p>
			<p><a href="logout.php">Click here to logout</a></p>
		</div>
	</body>
</html>