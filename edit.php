<?php
require_once "pdo.php";
session_start();
if ( isset($_POST['cancel'] ) ) {
    header("Location: user.php?user_id=".$_SESSION['user_id']);
    return;
}
if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['user_id'])) {
    if (strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['phone']) < 1){
      $_SESSION['error'] = "All fields are required";
      header("Location: edit.php?user_id=".$_REQUEST['user_id']);
      return;
    }
    else{
      if (! strpos($_POST['email'], "@")){
          $_SESSION["error"] = "Email must contain at-'@'";
          header("Location: edit.php?user_id=".$_REQUEST['user_id']);
          return;
        }
    if ( ! is_numeric($_POST['phone']) ){
         $_SESSION["error"] = "Phone Number must be numeric";
         header("Location: edit.php?user_id=".$_REQUEST['user_id']);
         return;
    }
    else{
      $sql10 = "UPDATE users SET name = :nm,
            email = :email, phone = :phone
            WHERE user_id = :user_id";
    $stmt10 = $pdo->prepare($sql10);
    $stmt10->execute(array(
        ':nm' => $_POST['name'],
        ':email' => $_POST['email'],
        ':phone' => $_POST['phone'],
        ':user_id' => $_POST['user_id'])
      );
    $_SESSION["success_edit"] = "Record updated Succesfully :)";
    header("Location: user.php?user_id=".$_SESSION['user_id']);
    return;
  }
}
}
if ( ! isset($_GET['user_id']) ) {
  $_SESSION['error_edit'] = "Missing user_id";
  header("Location: user.php?user_id=".$_SESSION['user_id']);
  return;
}
$stmt11 = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt11->execute(array(":xyz" => $_GET['user_id']));
$row11 = $stmt11->fetch(PDO::FETCH_ASSOC);
if ( $row11 === false ) {
    $_SESSION['error_edit'] = 'Bad value for autos_id';
    header("Location: user.php?user_id=".$_SESSION['user_id']);
    return;
}
?>
<html>
<head>
  <?php require_once "bootstrap.php"; ?>
<title>Edit User details</title>
</head>
<body>
<div class="container">
<h1>Editing User details</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
$name = $row11['name'];
$email = $row11['email'];
$phone = $row11['phone'];
$user_id = $row11['user_id'];
?>
<form method="post">
<p>User Name:
<input type="text" name="name" value="<?= $name ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $email ?>"></p>
<p>Phone Number:
<input type="text" name="phone" value="<?= $phone ?>"></p>
<input type="hidden" name="user_id" value="<?= $user_id ?>">
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
