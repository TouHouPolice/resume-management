<?php
//require_once "pdo.php";
function loginCheck(){
  if(isset($_SESSION['user_id'])){
    return true;
  }
  return false;
}

function checkAccess(){
  if(!loginCheck()){
    die("ACCESS DENIED");
  }
}

function readProfileById(&$pdo,$profile_id,$write=false){
  if (isset($profile_id)){
    $sql= "SELECT * FROM profile WHERE profile_id=:pid";//"SELECT first_name,last_name,email,headline,summary FROM profile WHERE profile_id=:pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pid'=>$profile_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row===false){
      $_SESSION['error']="Could not load profile";
      header("Location: index.php");
      return false;
    }
  }
  else{
    $_SESSION['error']="Missing profile_id";
    header("Location: index.php");
    return false;
  }
  if ($write){
    if ($_SESSION['user_id']!=$row['user_id']){
      $_SESSION['error']="You don't own the entry";
      header("Location: index.php");
      return false;
    }
  }
  return $row;
}

function readPosById(&$pdo,$profile_id,$write=false){
  if (isset($profile_id)){
    $sql= "SELECT year, description FROM position WHERE profile_id=:pid ORDER BY rank ASC";//"SELECT first_name,last_name,email,headline,summary FROM profile WHERE profile_id=:pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pid'=>$profile_id));
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($row===false){
      return false;
    }
  }
  return $row;

}

?>
