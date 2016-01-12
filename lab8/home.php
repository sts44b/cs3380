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


			// Get user log date and ip address for the day they first registered. 
			$registered = "SELECT log_date, ip_address FROM lab8.log WHERE username = $1 AND action = 'register'";
			pg_prepare($conn,"registered", $registered );
			$registered = pg_execute($conn, "registered", array($_SESSION['username']));
			$registered = pg_fetch_array($registered, null, PGSQL_ASSOC);

			// Get the user's description
			$description = "SELECT description FROM lab8.user_info WHERE username = $1";
			pg_prepare($conn, "description", $description);
			$description = pg_execute($conn, "description", array($_SESSION['username']));
			$description = pg_fetch_array($description, null, PGSQL_ASSOC);

			// Get the user's log information
			$logs = "SELECT action, ip_address, log_date FROM lab8.log WHERE username = $1";
			pg_prepare($conn, "logs", $logs);
			$logs = pg_execute($conn, "logs", array($_SESSION['username']));
			$numRows = pg_num_rows($logs);
		?>
		<!--Div to display the user's information-->
		<div class="container" align="center">
			<p>Username: <?=$_SESSION['username']?></p>
			<p>IP address: <?=$registered['ip_address']?></p>
			<p>Registration date: <?=$registered['log_date']?></p>
			<p>Description: <?=$description['description']?></p>
			<p>There were <?=$numRows?> rows returned</p>
			
			<table name="logins" class="table" border="1">
			<tr><th>Action</th><th>IP Address</th><th>Log Date</th></tr>
				<?php
					// Displays the table of log info.
					while ($line = pg_fetch_array($logs, null, PGSQL_ASSOC)) {
						echo "\t<tr>\n";
						foreach ($line as $col_value) {
							echo "\t\t<td>$col_value</td>\n";
						}
						echo "\t</tr>\n";
					}
				?>
			</table>
			<!--User options to update their description or logout-->
			<p><a href="update.php">Click</a> to update page.</p>
			<p><a href="logout.php">Click here to logout</a></p>
		</div>
	</body>
</html>