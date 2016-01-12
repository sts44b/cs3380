<html>
	<head>
		<title>Lab Four</title>
	</head>
	<body>
		<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
			
			<!-- Radio Buttons -->
			<div>
				<lable>Search for a : </lable>

				<input type="radio" name="search_by" value="country" id="country" checked="true">
				<label for="country">Country</label>
			
				<input type="radio" name="search_by" id="city" value="city">
				<label for="city">City</label>
			
				<input type="radio" name="search_by" id="language" value="language">
				<label for="language">Language</label>
			</div>

			<br>

			<!-- Text Input -->
			<div>
				<label for="search_for">That begins with:</label>
				<input type="text" name="search_for" id="search_for">
			</div>

			<br>

			<!-- Submit -->
			<input type="submit" value="Submit" name="submit">
		</form>

		<hr>

		<label>Or insert a new city by clicking on this </lable>
			<!--create a new php file to handle adding a new city-->
			<a href ="exec.php?action=insert">link</a>

		<hr>	

	</body>
</html>

<?php
	include "../secure/database.php";
		//Connecting, selecting database
		$dbconn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD)
			or die('Could not connect: ' . pg_last_error());

		if (isset($_POST['submit'])){

			$search = $_POST['search_for'];

			$my_clean_string = htmlspecialchars($search);


			if ($_POST['search_by'] == "country"){
			$result = pg_prepare($dbconn, "country_lookup", 'SELECT * FROM lab4.country WHERE name ILIKE $1 ORDER BY name') 
				or die("Query failed: " . pg_last_error());
			

			// Execute the prepared query.
			$result = pg_execute($dbconn, "country_lookup", array($my_clean_string .'%'))
				or die("Query failed: " . pg_last_error());

			}

			else if ($_POST['search_by'] == "city"){
			$result = pg_prepare($dbconn, "city_lookup", 'SELECT * FROM lab4.city WHERE name ILIKE $1 ORDER BY name') 
				or die("Query failed: " . pg_last_error());
			

			// Execute the prepared query.
			$result = pg_execute($dbconn, "city_lookup", array($my_clean_string .'%'))
				or die("Query failed: " . pg_last_error());

			}

			else if ($_POST['search_by'] == "language"){
			$result = pg_prepare($dbconn, "language_lookup", 'SELECT * FROM lab4.country_language WHERE language ILIKE $1 ORDER BY language') 
				or die("Query failed: " . pg_last_error());
			

			// Execute the prepared query.
			$result = pg_execute($dbconn, "language_lookup", array($my_clean_string .'%'))
				or die("Query failed: " . pg_last_error());

			}


			
		
		
		
			// Printing results in HTML
			echo "<p>There were <em>" . pg_num_rows($result) . "</em> rows returned.</p>";
			echo "<table border=\"1\">\n";
			echo "<tr>";

			echo "<th>Actions</th>";

			$i = 0;
			while( $i < pg_num_fields($result)){
				$field_names = pg_field_name($result, $i);
				echo "<th>$field_names</th>";
				$i++;
			}
			echo "</tr>";
			// Generates and fills out table from array returned from $result
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
				echo "\t<tr>\n";
				echo "<td>"
				?>
				
					<form method='POST' action='exec.php'>
						<?php 
						if ($_POST['search_by'] == 'city'){?>
							<input type='submit' value='edit' name='action'>
							<input type='submit' value='delete' name='action'>
							<input type='hidden' name='pk' value="<?= $line['id']?>">
							<input type='hidden' name='table' value='city'>
						<?php }
						else if ($_POST['search_by'] == 'country'){?>
							<input type='submit' value='edit' name='action'>
							<input type='submit' value='delete' name='action'>
							<input type='hidden' name='pk' value="<?= $line['country_code']?>">
							<input type='hidden' name='table' value='country'>
						<?php }
						else if ($_POST['search_by'] == 'language'){?>
							<input type='submit' value='edit' name='action'>
							<input type='submit' value='delete' name='action'>
							<input type='hidden' name='pk' value="<?= $line['country_code']?>">
							<input type='hidden' name='pk2' value="<?= $line['language']?>">
							<input type='hidden' name='table' value='language'>
						<?php }?>	
					</form>

				<?php 

				echo "</td>";

				foreach ($line as $col_value) {
					echo "\t\t<td>$col_value</td>\n";
				}
				echo "\t</tr>\n";
			}
			echo "</table>\n";
			//Free result set
			pg_free_result($result);
			// Closes connection
			pg_close($conn);
		}
			

?>
