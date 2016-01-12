<html>
<head/>

    <style>
      .strong{
        font-weight: bold;
      }
    </style>
<body>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <table border="1">
     <tr><td>Number of Rows:</td><td><input type="text" name="rows" /></td></tr>
     <tr><td>Number of Columns:</td><td><select name="columns">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="4">4</option>
    <option value="8">8</option>
    <option value="16">16</option>

  </select>
</td></tr>
   <tr><td>Operation:</td><td><input type="radio" name="operation" value="multiplication" checked="yes">Multiplication</input><br/>
  <input type="radio" name="operation" value="addition">Addition</input>
  </td></tr>
  </tr><td colspan="2" align="center"><input type="submit" name="submit" value="Generate" /></td></tr>
</table>
</form>

<?php
    //assign values to the variables rows and columns
   $rows = $_POST[rows];
   $columns = $_POST[columns];
   
    //check that the generate button was pressed
    if (isset($_POST['submit'])){
      //check that the value entered in the rows box is both positive and numeric
      if (is_numeric($rows) == false || $rows < 0){
        echo "invalid rows and/or columns parameters.";
      }

      else{
        //if the multiplication radio button is selected print this statement
        if ($_POST[operation] == "multiplication"){
        echo "The $rows x $columns multiplication table.";
        }

        //if the addition radio button is selected print this statement
        elseif ($_POST[operation] == "addition"){
          echo "The $rows x $columns addition table.";
        }

        echo "<table border = 1>";

        echo "<tr>";

          //print the column headers in the first row
          for ($i=0; $i < $columns + 1; $i++) { 
            echo "<td class = 'strong'>" . $i . "</td>";
          }

        echo "</tr>";

        for ($j=1; $j < $rows + 1; $j++) { 
          echo "<tr>";
            //print the row headers in bold
            echo "<td class = 'strong'>" . $j . "</td>";

            for ($k=1; $k < $columns + 1; $k++) { 
              
              //if multiplication is selected then multiply
              if ($_POST[operation] == "multiplication"){
                echo "<td>" . $j * $k . "</td>";
              }

              //if addition is selected then add
              elseif ($_POST[operation] == "addition"){
                echo "<td>" . ($j + $k) . "</td>";
              }  
            }
          echo "</tr>";
        }

        echo "</table>";
      }
    }
?>
</body>
</html>