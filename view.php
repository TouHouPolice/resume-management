<?php
session_start();
require_once "pdo.php";
require_once "utility.php";
// if (isset($_GET['profile_id'])){
//   $sql="SELECT first_name,last_name,email,headline,summary FROM profile WHERE profile_id=:pid";
//   $stmt = $pdo->prepare($sql);
//   $stmt->execute(array(':pid'=>$_GET['profile_id']));
//   $row = $stmt->fetch(PDO::FETCH_ASSOC);
//   if($row===false){
//     $_SESSION['error']="Could not load profile";
//     header("Location: index.php");
//   }
//
// }
// else{
//   $_SESSION['error']="Missing profile_id";
//   header("Location: index.php");
// }
$row=readProfileById($pdo,$_GET['profile_id']);
$posRows=readPosById($pdo,$_GET['profile_id']);


?>

<!DOCTYPE html>
<html>
<head>
<title>View Page</title>

<?php require_once 'bootstrap.php' ?>

</head>
<body>
  <? require_once './components/layout.php' ?>
  <div class="container">
    <h1>Profile information</h1>
    <div >
      <p class="h3">First Name:
      <? echo htmlentities($row['first_name']) ?></p>
      <p class="h3">Last Name:
      <? echo htmlentities($row['last_name']) ?></p>
      <p class="h3">Email:
      <? echo htmlentities($row['email']) ?></p>
      <p class="h3">Headline:<br/>
      <? echo htmlentities($row['headline']) ?></p>
      <p class="h3">Summary:<br/>
      <? echo htmlentities($row['summary']) ?></p>
    </div>

  <?php
    if($posRows!==false){
      echo "<p class='h3'>Position</p>
      <ul>";

      foreach ($posRows as $posRow){

        echo "<li>".htmlentities($posRow['year']).": ".htmlentities($posRow['description'])."</li>";
      }
      echo "</ul>";
    }
   ?>

  <a href="index.php">Back</a>
  </div>

</html>
