<?php
session_start();
require_once "pdo.php";
if(isset($_POST["return"])){
  unset($_SESSION["transfer"]);
  header("Location: index.php");
  return;
}
if(isset($_POST["edit"])){
  unset($_SESSION["transfer"]);
  header("Location: edit.php?user_id=".$_SESSION['user_id']);
  return;
}
if(isset($_POST["back"])){
  unset($_SESSION["transfer"]);
  header("Location: view.php");
  return;
}
if(isset($_POST["del"])){
  unset($_SESSION["transfer"]);
  header("Location: delete.php?user_id=".$_SESSION['user_id']);
  return;
}
if(isset($_POST["cancel"])){
  unset($_SESSION["transfer"]);
  header("Location: user.php?user_id=".$_SESSION['user_id']);
  return;
}
if(isset($_POST["trans"])){
  unset($_SESSION["transfer"]);
  header("Location: transfer.php");
  return;
}
if(isset($_POST["transfer"])){
  $_SESSION["transfer"] = "transfering";
  header("Location: user.php?user_id=".$_REQUEST['user_id']);
  return;
}
else{
  $_SESSION["user_id"] = $_GET["user_id"];
}
if(isset($_POST["select"]) && isset($_POST["amount"])){
  if($_POST["select"] == "-- Select a User --" || strlen($_POST["amount"]) < 1){
    if($_POST["select"] == "-- Select a User --" && strlen($_POST["amount"]) < 1){
      $_SESSION["error"] = "Please fill the necessary details";
      $_SESSION["transfer"] = "transfering";
      header("Location: user.php?user_id=".$_SESSION['user_id']);
      return;
    }
    elseif ( strlen($_POST["amount"]) < 1) {
      $_SESSION["error"] = "Please Enter the amount";
      $_SESSION["transfer"] = "transfering";
      header("Location: user.php?user_id=".$_SESSION['user_id']);
      return;
    }
    else{
      $_SESSION["error"] = "Please Select User";
      $_SESSION["transfer"] = "transfering";
      header("Location: user.php?user_id=".$_SESSION['user_id']);
      return;
    }
  }
  else{
    $sql3 = "SELECT current_credit, name FROM users WHERE user_id = :ssid";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute(array(
        ':ssid' => $_SESSION['user_id'])
      );
    $row1 = $stmt3->fetch(PDO::FETCH_ASSOC);
    if ($_POST["amount"] > $row1["current_credit"]){
      $_SESSION["error"] = "Insufficient Balance :(";
      $_SESSION["transfer"] = "transfering";
      header("Location: user.php?user_id=".$_SESSION['user_id']);
      return;
    }
    else{
    $sql1 = "UPDATE users SET current_credit = current_credit + :ccr
          WHERE name = :nm";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute(array(
        ':ccr' => htmlentities($_POST['amount']),
        ':nm' => htmlentities($_POST['select']))
      );
    $sql2 = "UPDATE users SET current_credit = current_credit - :ccr1
            WHERE user_id = :uid";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute(array(
          ':ccr1' => htmlentities($_POST['amount']),
          ':uid' => htmlentities($_SESSION['user_id']))
        );
        $_SESSION["success"] = "Amount Credited Succesfully !! :)";
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-yy h:i:s');
        $stmt4 = $pdo->prepare("INSERT INTO transfers (user_debited, user_credited, credit, Date_and_Time) VALUES ( :ud, :uc, :ccr2, :dt)");
        $stmt4->execute(array(
       ':ud' => htmlentities($row1["name"]),
       ':uc' => htmlentities($_POST['select']),
       ':ccr2' => htmlentities($_POST['amount']),
       ':dt' => htmlentities($date))
     );
        header("Location: view.php");
        return;
  }
}
}
$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head><?php
if (!isset($_SESSION["transfer"])){
echo '<title>User Page</title>';
}
else{
  echo '<title>Transfering....</title>';
}
?>
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
/**input[type=submit] {
  padding: 10px 10px;
  margin: 10em 0.5em;
  box-sizing: border-box;
}**/
</style>
</head>
<body>
  <div class="container">
  <h1 style="text-align: center;">Details about the user</h1>
  <?php
  if ( isset($_SESSION['error_edit']) ) {
      echo '<p style="color:red">'.$_SESSION['error_edit']."</p>\n";
      unset($_SESSION['error_edit']);
  }
  if(isset($_SESSION['success_edit'])){
    echo '<p style="color:green">'.$_SESSION['success_edit']."</p>\n";
    unset($_SESSION['success_edit']);
  }
  $user_id = $row["user_id"];
  $name = $row["name"];
  $phone = $row["phone"];
  $email = $row["email"];
  $current_credit = htmlentities($row["current_credit"]);
  ?>
  <table style = "border: 4px solid black; width: 100%; height: 20%;">
    <tr>
      <th>User Id</th>
      <th>User Name</th>
      <th>Email</th>
      <th>Phone Number</th>
      <th>Current credit</th>
    </tr>
    <tr>
      <td><?=$user_id?></td>
      <td><?=$name?></td>
      <td><?=$email?></td>
      <td><?=$phone?></td>
      <td><?=$current_credit?></td>
    </tr>
  </table>
  <?php
  if (!isset($_SESSION["transfer"])){
  echo "<form method='post'>";
  echo "<input type='submit' name='edit' value='Edit User details' style='padding: 0.5em 0.5em; margin: 1em 0em'>";
  echo "<input type='submit' name='transfer' value='Transfer Credit' style='padding: 0.5em 0.5em; margin: 10em 35em;'>";
  echo "<input type='submit' name='del' value= 'Delete User' style='padding: 0.5em 0.5em; margin: 1em -10em;'>";
  echo "</form>";
}
else{
  unset($_SESSION["transfer"]);
  $stmt = $pdo->query("SELECT name FROM users WHERE user_id !=".$_SESSION['user_id']);
  echo '<h2 style= "margin: 2em 0;">Fill the details below to transfer and then click "Transfer"</h2>';
  if ( isset($_SESSION['error']) ) {
      echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
      unset($_SESSION['error']);
      $_SESSION["transfer"] = "transfering";
  }
  echo '<form method="post">';
  echo '<p>Select User:';
  echo '<select name = "select">';
  echo '<option>-- Select a User --</option>';
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
  echo '<option>';
  echo $row['name'];
  echo '</option>';
}
  echo '</select></p>';
  echo '<p>Transfer amount: ';
  echo '<input type="text" name="amount"/></p>';
  echo '<p><input type="submit" name="trans_amount" value="Transfer">  ';
  echo '<input type="submit" name="cancel" value="Cancel"></p>';
  echo '</form>';
}
  ?>
  <table style= "width: 50em; margin-left: 15%;">
    <tr>
  <form method="post">
    <td style = "border: none;"><input type="submit" name="return" value="Return to Homepage" style = "padding: 5px 5px; margin: 1em 0;"></td>
    <td style = "border: none;"><input type="submit" name="back" value="Back to all users' page"  style = "padding: 5px 5px;"></td>
    <td style = "border: none;"><input type="submit" name="trans" value="View all transfers record"  style = "padding: 5px 5px;"></td>
  </form></tr>
  </table>
</div>
</body>
</html>
