<?php
session_start();
require_once "pdo.php";
if(isset($_POST["view"])){
  header("Location: view.php");
  return;
}
if(isset($_POST["trans"])){
  unset($_SESSION["transfer"]);
  header("Location: transfer.php");
  return;
}
?>
<html>
<head><title>Credit Management: Home Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
  <div class="container">
  <h1 style="text-align: center; margin: 10% 0;">Welcome to Credit Management</h1>
  <form method="post">
    <input type="submit" name="view" value="View Users" style = "padding: 10px 10px; margin: 0 45.5%;">
  </form>
  <form method="post">
    <input type="submit" name="trans" value="View all transfers record" style = "padding: 10px 10px; margin: 0 42%;">
  </form>
</div>
</body>
</html>
