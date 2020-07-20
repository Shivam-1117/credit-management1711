<?php
session_start();
require_once "pdo.php";
if(isset($_POST["return"])){
  header("Location: index.php");
  return;
}
if(isset($_POST["trans"])){
  unset($_SESSION["transfer"]);
  header("Location: transfer.php");
  return;
}
if(isset($_POST["add"])){
  header("Location: add.php");
  return;
}
?>
<html>
<head><title>Viewing All Users</title>
<?php require_once "bootstrap.php"; ?>
<style>
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
  <h1 style="text-align: center;">Users in the Database</h1>
  <?php
  if(isset($_SESSION['success'])){
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
  }
  if ( isset($_SESSION['error']) ) {
      echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
      unset($_SESSION['error']);
  }
  echo('<table style= "border: 4px solid black; width: 50em; height: 80%; margin-left: 15%;">'."\n");
  echo '<tr><th>User Name</th>';
  echo '<th>View User</th></tr></tr>';
  $stmt = $pdo->query("SELECT * FROM users");
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo "<tr><td>";
      echo($row['name']);
      echo('</td><td><a href="user.php?user_id='.$row['user_id'].'">View</a>');
      echo("</td></tr>\n");
  }
  echo "</table>";
?>
<table style= "width: 50em; margin-left: 15%;">
  <tr>
<form method="post">
  <td style = "border: none;"><input type="submit" name="return" value="Return to Homepage" style = "padding: 5px 5px; margin: 1em 0;"></td>
  <td style = "border: none;"><input type="submit" name="trans" value="View all transfers record"  style = "padding: 5px 5px;"></td>
  <td style = "border: none;"><input type="submit" name="add" value="Add new User"  style = "padding: 5px 5px;"></td>
</form></tr>
</table>
</div>
</body>
</html>
