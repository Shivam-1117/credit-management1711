<?php
session_start();
if ( isset($_POST['cancel']) ) {
    header('Location: view.php');
    return;
}
require_once "pdo.php";
if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])
     && isset($_POST['current'])) {
       if (strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['phone']) < 1 || strlen($_POST['current']) < 1){
         $_SESSION['error'] = "All fields are required";
         header("Location: add.php");
         return;
       }
    else{
      if (! strpos($_POST['email'], "@")){
          $_SESSION["error"] = "Email must contain at-'@'";
          header("Location: add.php");
          return;
        }
    if ( ! is_numeric($_POST['phone']) || ! is_numeric($_POST['current']) ){
         $_SESSION["error"] = "Phone number and Current Credit must be numeric";
         header("Location: add.php");
         return;
    }
    else{
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, current_credit) VALUES ( :name, :email, :phone, :ccr)");
    $stmt->execute(array(
    ':name' => htmlentities($_POST['name']),
    ':email' => htmlentities($_POST['email']),
    ':phone' => htmlentities($_POST['phone']),
    ':ccr' => htmlentities($_POST['current']))
    );
    $_SESSION["success"] = "Record added Succesfully :)";
    header("Location: view.php");
    return;
}
}
}
?>
<html>
<head><?php require_once "bootstrap.php"; ?>
</head><body>
<div class="container">
  <h1>Add a new User by filling the details below</h1>
  <?php
      if ( isset($_SESSION["error"]) ) {
          echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
          unset($_SESSION["error"]);
      }
  ?>
  <form method="post">
  <p>User Name:
  <input type="text" name="name" size="60"/></p>
  <p>Email:
  <input type="text" name="email"/></p>
  <p>Phone Number:
  <input type="text" name="phone"/></p>
  <p>Current Credit:
  <input type="text" name="current"/></p>
  <input type="submit" value="Add">
  <input type="submit" name="cancel" value="Cancel">
  </form>
</div>
</body>
</html>
