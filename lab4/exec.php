<!doctype html> 

<html> 

	<head> 
		<title>New City</title> 
	</head> 

	

	<body>
				<?php
				include("../secure/database.php");
					$conn = pg_connect(HOST ." " . DBNAME . " " . USERNAME . " " . PASSWORD)
						or die('Could not connect:' . pg_last_error());
				


				?>			


	<?php
		if(isset($_GET['action'])){
			if ($_GET['action'] == 'insert'){
	?>

	<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
		<p>Enter data for the city to be added:</p>
  		<table border="1">
     		<tr><td>Name</td><td><input type="text" name="name" /></td></tr>
     		<tr><td>Country Code</td><td><select name="country_code">
     			<?php
					$countries = 'SELECT * FROM lab4.country ORDER BY name';

					$listresult = pg_prepare($conn, "list", $countries);
					$listresult = pg_execute($conn, "list", array());
					
    				while($line = pg_fetch_array($listresult, null, PGSQL_ASSOC)){
						echo "<option value=\"".$line['country_code']."\">".$line['name']."</option>";
					}
    			?>
  			</select>
			</td></tr>
   			<tr><td>District</td><td><input type="text" name="district" /></td></tr>
   			<tr><td>Population</td><td><input type="text" name="population" /></td></tr>
		</table>

		<input type="submit" name="action" value="Save" />
  		<input type="submit" name="action" value="Cancel" />
	</form>
	<?php
		}
	}

		if($_POST['action'] == 'edit'){

			$key=$_POST['pk'];

			if($_POST['table'] == 'country'){
				$query = 'SELECT * FROM lab4.country WHERE country_code = \''.$key.'\';';
				$query = pg_prepare($conn, "edit_country", $query);
				$query = pg_execute($conn, "edit_country", array());

				echo "<table border=\"1\">\n";


				$i = 0;


				// Generates and fills out table from array returned from $result
				$editable_fields = array("indep_year", "population", "local_name", "government_form");
				?><form method="POST" action="exec.php"><?php
				while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)){
					foreach ($line as $col_value) {
					
					$field_names = pg_field_name($query, $i);

					

					echo "\t<tr>\n";

					if (in_array($field_names, $editable_fields)) {
						echo "\t\t<td><strong>$field_names</strong></td>\n";
					}
					else{	
						echo "<td>$field_names</td>";
					}	
					$i++;
					

					echo "</td>";

					if (in_array($field_names, $editable_fields)) {
						echo "\t\t<td><input type='text' name='$field_names' value=$col_value></td>\n";
					}
					else{	
						echo "\t\t<td>$col_value</td>\n";
					}	
				}
				echo "\t</tr>\n";
			}
			echo "</table>\n";

			echo "<input type='submit' name='action' value='Save' />";
  			echo "<input type='button' value='Cancel' onClick='top.location.href=\"lab4.php\";'/>";

  			?></form><?php
			}

			else if ($_POST['table'] == 'city'){

				$query = 'SELECT * FROM lab4.city WHERE id = \''.$key.'\';';
				$query = pg_prepare($conn, "edit_city", $query);
				$query = pg_execute($conn, "edit_city", array());

				echo "<table border=\"1\">\n";


				$i = 0;


				// Generates and fills out table from array returned from $result
				$editable_fields = array("district", "population");
				
				while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)){
					foreach ($line as $col_value) {
					
					$field_names = pg_field_name($query, $i);

					

					echo "\t<tr>\n";

					if (in_array($field_names, $editable_fields)) {
						echo "\t\t<td><strong>$field_names</strong></td>\n";
					}
					else{	
						echo "<td>$field_names</td>";
					}	
					$i++;
					

					echo "</td>";

					if (in_array($field_names, $editable_fields)) {
						echo "\t\t<td><input type='text' name='editable' value=$col_value></td>\n";
					}
					else{	
						echo "\t\t<td>$col_value</td>\n";
					}	
				}
				echo "\t</tr>\n";
			}
			echo "</table>\n";

			echo "<input type='submit' name='action' value='Save' />";
  			echo "<input type='button' value='Cancel' onClick='top.location.href=\"lab4.php\";'/>";
			}
			else if($_POST['table'] == 'language'){
				$key2 = $_POST['pk2'];
				$query = 'SELECT * FROM lab4.country_language WHERE country_code = \''.$key.'\' AND language = \''.$key2.'\';';
				$query = pg_prepare($conn, "edit_language", $query);
				$query = pg_execute($conn, "edit_language", array());

				echo "<table border=\"1\">\n";


				$i = 0;


				// Generates and fills out table from array returned from $result
				$editable_fields = array("is_official", "percentage");
				?><form method="POST" action="exec.php"><?php
				
				while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)){
					foreach ($line as $col_value) {
					
					$field_names = pg_field_name($query, $i);

					

					echo "\t<tr>\n";

					if (in_array($field_names, $editable_fields)) {
						echo "\t\t<td><strong>$field_names</strong></td>\n";
					}
					else{	
						echo "<td>$field_names</td>";
					}	
					$i++;
					

					echo "</td>";

					if (in_array($field_names, $editable_fields)) {
						echo "\t\t<td><input type='text' name='editable' value=$col_value></td>\n";
					}
					else{	
						echo "\t\t<td>$col_value</td>\n";
					}	
				}
				echo "\t</tr>\n";
			}
			echo "</table>\n";

			echo "<input type='submit' name='action' value='Save' />";
  			echo "<input type='button' value='Cancel' onClick='top.location.href=\"lab4.php\";'/>";
			}

			?></form><?php

		}

		/*This block of code executes when the delete button is selected then searches the table using key values and deletes
		the appropriate row.
		*/
		else if($_POST['action'] == 'delete'){
			$key=$_POST['pk']; 

			if($_POST['table'] == 'country'){
				$query = 'DELETE FROM lab4.country WHERE country_code = \''.$key.'\';';
			}
			else if($_POST['table'] == 'city'){
				$query = 'DELETE FROM lab4.city WHERE id = \''.$key.'\';';
			}
			else if($_POST['table'] == 'language'){
				$key2=$_POST['pk2'];
				$query = 'DELETE FROM lab4.country_language WHERE country_code = \''.$key.'\' AND language = \''.$key2.'\';';
			}

			pg_prepare($conn, "delete", $query);
			if(pg_execute($conn, "delete", array())){
				echo "Delete was successful<br/>";
				echo "Return to <a href=\"lab4.php\">search</a>";
			}

			else{
				echo "Delete unsuccessful<br/>";
				echo "Return to <a href=\"lab4.php\">search</a>";
			}
		}
	?>







	</body>

</html>