<?php
session_start();
require_once "pdo.php";
if(isset($_POST["cancel"])){
  header("Location: user.php?user_id=".$_SESSION['user_id']);
  return;
}
if ( isset($_POST['delete']) && isset($_POST['user_id']) ) {
  $sql7 = "DELETE FROM users WHERE user_id = :zip";
  $stmt7 = $pdo->prepare($sql7);
  $stmt7->execute(array(':zip' => $_POST['user_id']));
  $_SESSION['success'] = 'Record deleted successfully :)';
    header( 'Location: view.php' ) ;
    return;
}
if ( ! isset($_GET['user_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: view.php') ;
  return;
}
$sql8 = "SELECT user_id, name FROM users WHERE user_id = :ssid";
$stmt8 = $pdo->prepare($sql8);
$stmt8->execute(array(
    ':ssid' => $_GET['user_id'])
  );
$row8 = $stmt8->fetch(PDO::FETCH_ASSOC);
if ( $row8 === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header('Location: view.php') ;
    return;
}
?>
<html><head>
  <?php require_once "bootstrap.php"; ?>
<title>Deleting....</title></head><body>
<div class="container">
<p><b>Confirm: Deleting</b></p>
<form method="post">
<input type="hidden" name="user_id" value="<?= $row8['user_id'] ?>">
<input type="submit" value="Delete" name="delete">
<input type="submit" value="Cancel" name="cancel">
</form>
</div>
<body>
