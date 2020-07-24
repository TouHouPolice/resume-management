<?php
require_once "utility.php";
require_once "pdo.php";
session_start();

checkAccess();

$failure=false;

if (isset($_POST['cancel'])){
  header('Location: index.php');
  return;
}

if(isset($_SESSION['error'])){
  $failure=$_SESSION['error'];
  unset($_SESSION['error']);
}

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])){

  if(!(strlen($_POST['first_name'])>0 && strlen($_POST['last_name'])>0 && strlen($_POST['email'])>0 && strlen($_POST['headline'])>0 && strlen($_POST['summary'])>0 && strlen($_POST['profile_id'])>0)){
    $_SESSION['error']="All fields are required";
  }
  elseif (strpos($_POST['email'],"@")==false){
    $_SESSION['error']="Email address must contain @";
  }
  else{  //Proceed to insert
    $sql = "UPDATE profile SET first_name=:fn,last_name=:ln,email=:em,headline=:he,summary=:su WHERE profile_id=:pid";
    $stmt= $pdo->prepare($sql);
    $stmt->execute(array(
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':he' => $_POST['headline'],
    ':su' => $_POST['summary'],
    ':pid'=> $_POST['profile_id'])
);
    //$success=true;
    $_SESSION['success']="Profile updated";
    header("Location: index.php");
    return;
  }
  header("Location: edit.php?profile_id=".$_POST['profile_id']);
  return;
}


$row=readProfileById($pdo,$_GET['profile_id'],true);

?>


 <html>
 <head>
 <title>LIFENGYUN's Profile Edit</title>

 <?php require_once "bootstrap.php"; ?>

 </head>
 <body>
 <? require_once './components/layout.php' ?>
 <div class="container">
 <h1>Editing Profile</h1>
 <?php
 if ($failure){
   echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
 }
 ?>
 <form method="post" action="edit.php">
 <p>First Name:
 <input type="text" name="first_name" size="60"
 value= <? echo htmlentities($row['first_name']) ?>
 /></p>
 <p>Last Name:
 <input type="text" name="last_name" size="60"
 value=<? echo htmlentities($row['last_name']) ?>
 /></p>
 <p>Email:
 <input type="text" name="email" size="30"
 value=<? echo htmlentities($row['email']) ?>
 /></p>
 <p>Headline:<br/>
 <input type="text" name="headline" size="80"
 value=<? echo htmlentities($row['headline']) ?>
 /></p>
 <p>Summary:<br/>
 <textarea name="summary" rows="8" cols="80">
 <? echo htmlentities($row['summary']) ?></textarea>
 <p>
 <input type="hidden" name="profile_id"
 value=<? echo htmlentities($_GET['profile_id']) ?>
 />
 <input type="submit" value="Save">
 <input type="submit" name="cancel" value="Cancel">
 </p>
 </form>
 </div>
 </body>
 </html>
