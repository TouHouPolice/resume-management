<?php
require_once "utility.php";
require_once "pdo.php";
session_start();

checkAccess();

if (isset($_POST['cancel'])){
  header('Location: index.php');
  return;
}

if (isset($_POST['delete'])){
  $sql="DELETE FROM profile WHERE profile_id=:pid";
  $stmt=$pdo->prepare($sql);
  $stmt->execute(array(':pid'=>$_POST['profile_id']));
  $_SESSION['success']="Profile deleted";
  header('Location: index.php');
  return;
}

$row=readProfileById($pdo,$_GET['profile_id'],true);
 ?>


 <!DOCTYPE html>
 <html>
 <head>
 <title>LIFENGYUN's Profile Delete</title>
 <!-- bootstrap.php - this is HTML -->

 <!-- Latest compiled and minified CSS -->
 <link rel="stylesheet"
     href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
     integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
     crossorigin="anonymous">

 <!-- Optional theme -->
 <link rel="stylesheet"
     href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
     integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
     crossorigin="anonymous">

 </head>
 <body>
 <? require_once './components/layout.php' ?>
 <div class="container">
 <h1>Deleteing Profile</h1>
 <form method="post" action="delete.php">
 <p>First Name:
 <? echo htmlentities($row['first_name']) ?></p>
 <p>Last Name:
 <? echo htmlentities($row['last_name']) ?></p>
 <input type="hidden" name="profile_id"
 value=<? echo htmlentities($_GET['profile_id']) ?>
 />
 <input type="submit" name="delete" value="Delete">
 <input type="submit" name="cancel" value="Cancel">
 </p>
 </form>
 </div>
 </body>
 </html>
