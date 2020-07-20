<?php
session_start();
require_once "pdo.php";
if(isset($_POST["return"])){
  header("Location: index.php");
  return;
}
if(isset($_POST["clear"])){
  $stmt6 = $pdo->query("DELETE FROM transfers");
  header("Location: transfer.php");
  return;
}
?>
<html>
<head><title>Transfers Record</title>
<?php require_once "bootstrap.php"; ?>
<style>
table{
  border: 4px solid black;
  width: 50em;
  margin-left: 15%;
}
th{
  height: 50px;
  text-align: center;
  border-left: 2px solid black;
  border-right: 2px solid black;
  border-bottom: 4px solid black;
}
td{
  text-align: center;
  border-left: 2px solid black;
  border-right: 2px solid black;
  border-bottom: 2px solid black;
}
</style>
</head>
<body>
  <div class="container">
  <h1 style="text-align: center;">Transfers Record</h1>
  <?php
  echo('<table>'."\n");
  echo '<tr><th>Transfer Id</th>';
  echo '<th>User Debited</th>';
  echo '<th>User Credited</th>';
  echo '<th>Credit amount</th>';
  echo '<th>Date and Time</th></tr></tr>';
  $stmt = $pdo->query("SELECT * FROM transfers");
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo "<tr><td>";
      echo($row['transfer_id']);
      echo('</td><td>');
      echo($row['user_debited']);
      echo('</td><td>');
      echo($row['user_credited']);
      echo('</td><td>');
      echo($row['credit']);
      echo('</td><td>');
      echo($row['Date_and_Time']);
      echo("</td></tr>\n");
  }
  echo "</table>";
?>
<form method="post">
  <input type="submit" name="clear" value="Clear all transfer records" style = "padding: 10px 10px; margin: 1% 41%;">
</form>
<form method="post">
  <input type="submit" name="return" value="Return to Homepage" style = "padding: 10px 10px; margin: 5% 42%;">
</form>
</div>
</body>
</html>
