<?php
require_once "pdo.php";
require_once "utility.php";
session_start();

$sql="SELECT profile_id,user_id,first_name, last_name,headline FROM profile";
$stmt=$pdo->prepare($sql);
$stmt->execute();
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>LIFENGYUN - Index</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>

<?php require_once "./components/layout.php"; ?>

<div class="container">
<h1>Resume Management</h1>

<?php

if(isset($_SESSION['error'])){
  echo "<p style=color:red>".$_SESSION['error']."</p>";
  unset($_SESSION['error']);
}
elseif (isset($_SESSION['success'])) {
  echo "<p style=color:green>".$_SESSION['success']."</p>";
  unset($_SESSION['success']);
}
if (loginCheck()){
  
  echo "
  <div style='padding-bottom:20px;'>
  <a class='btn btn-primary btn-lg active' role='button' aria-pressed='true' href='logout.php'>Log out</a>
  <a class='btn btn-primary btn-lg active' role='button' aria-pressed='true' href='add.php'>Add New Entry</a>
  </div>
  ";


}
else{
  echo "<div style='padding-bottom:20px;'>
  <a class='btn btn-primary btn-lg active' role='button' aria-pressed='true' href='login.php'>Please log in</a>
  </div>";
}
?>


<?php
if (count($rows)>0){
  if (loginCheck()){
    echo "<table class='table table-dark'>
            <tr>
              <th>Name</th>
              <th>Headline</th>
              <th>Action</th>
            </tr>";
    foreach ($rows as $row){
      $viewlink="view.php?profile_id=".$row['profile_id'];
      $editlink="edit.php?profile_id=".$row['profile_id'];
      $deletelink="delete.php?profile_id=".$row['profile_id'];
      $fullname=$row['first_name'].' '.$row['last_name'];
      echo
      "<tr>
          <td>
            <a href=".$viewlink.">".$fullname."</a>
          </td>
          <td>
          ".$row['headline']."
          </td>
          <td>
            <a href=".$editlink.">Edit</a>
            <a href=".$deletelink.">Delete</a>
          </td>
        </tr>
      ";
    }
    echo "</table>";
  }
  else{
    echo "<table class='table table-dark'>
            <tr>
              <th>Name</th>
              <th>Headline</th>
            </tr>";

    foreach ($rows as $row){
      $viewlink="view.php?profile_id=".$row['profile_id'];
      $fullname=$row['first_name'].' '.$row['last_name'];
      echo
      "<tr>
        <td>
          <a href=".$viewlink.">".$fullname."</a>
        </td>
        <td>
        ".$row['headline']."
        </td>
      </tr>";
  
    }
    echo "</table>";
  }
}
?>

</div>
</body>
