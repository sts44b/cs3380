<!doctype html> 

<html> 

	<head> 
		<title>Lab Two</title> 
		<meta charset='UTF-8'>
		<style>
			/* corrects margin for text */
			p{
				margin-top:0px;
				margin-bottom:20px;
			}
		</style>
	</head> 

	<body>
		<form method="POST" action="?= $_SERVER['PHP_SELF'] ?>">
			<select name="query">
				<?php
					for($i = 1;$i <= 12; ++$i){
						echo "<option value=" .$i.">Query $i</option>";
					}	
				?>
			</select>

			<input type="submit" value="Execute" name="submit">


		</form>

		<br></br>
		<hr></hr>
		<br></br>

	

	<?php

		print_r($_POST); /*prints out everything sent in a form*/
		//Connecting, selecting database
		include("../secure/database.php");
		$conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD)
			or die('Could not connect: ' . pg_last_error());


		if (isset($_POST['submit'])){
			switch ($_POST['query']){
				case 1:
				$query = 'SELECT * FROM lab2.cities';
							/*WHERE surface_area >2000000
							ORDER BY gnp';*/
				break;
			}	
		}
		// Performing SQL query, NOTE: you must have a cities table for this to work.
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		// Printing results in HTML
		echo "<table>\n";
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		echo "\t<tr>\n";
		foreach ($line as $col_value) {
		echo "\t\t<td>$col_value</td>\n";
		}
		echo "\t</tr>\n";
		}
		echo "</table>\n";
		// Free resultset
		pg_free_result($result);
		// Closing connection
		pg_close($conn);
			

	?>

	</body> 

</html>