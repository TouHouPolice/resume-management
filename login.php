<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
//$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;  // If we have no POST data
if (isset($_SESSION['error'])){
  $failure=$_SESSION['error'];
  unset($_SESSION['error']);

}

require_once "pdo.php";

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {

    if (strlen($_POST['email'])<1 || strlen($_POST['pass'])<1)
    {
      $_SESSION['error']="Email and password are required";
    }
    elseif (strpos($_POST['email'], '@') === false)
    {
      $_SESSION['error']="Email must have an at-sign (@)";
    }
    else
    {
        $check = hash('md5', $salt.$_POST['pass']);
        $qry="SELECT user_id, name FROM users WHERE email = :em AND password = :pw";
        $stmt=$pdo->prepare($qry);
        $stmt->execute(array(':em'=>$_POST['email'],':pw'=>$check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $row!==false ) {
            // Redirect the browser to index.php
            error_log("Login success ".$_POST['email']);
            $_SESSION['name']=$row['name'];
            $_SESSION['user_id']=$row['user_id'];
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error']="Incorrect password";
            //$failure = "Incorrect password";
            error_log("Login fail ".$_POST['email']." $check");



        }
    }
    header("Location: login.php");
    return;
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Login Page</title>
</head>
<script>
  function doValidation(){
    console.log('Validating...');
    try{
      pw=document.getElementById('id_1723').value;
      em=document.getElementById('nam').value;
      if(pw==null||pw==""||em==null||em==""){

        alert('Both fields must be filled out');
        return false;
      }
      if(!em.includes("@")){
        alert('Invalid email address');
        return false;
      }
      return true;
    }
    catch(e){
      return false;
    }
    return false;
  }
</script>
<body>
<? require_once './components/layout.php' ?>
<div class="container">
<h1>Please log in</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="POST">
<div class="form-group row">
  <label for="nam" class="col-sm-2 col-form-label">Email</label>
  <div class="col-sm-10">
    <input type="text" class="form-control-plaintext" name="email" id="nam"><br/>
  </div>
</div>

<div class="form-group row">
  <label class="col-sm-2 col-form-label" for="id_1723">Password</label>
  <div class="col-sm-10">
    <input type="text" name="pass" id="id_1723"><br/>
  </div>
</div>
<input class="btn btn-primary" type="submit" onclick="return doValidation()" value="Log In">
<input class="btn btn-dark" type="submit" name="cancel" value="Cancel">
</form>
<p>
The password is php123.
The email is umsi@umich.edu
<!-- Hint: The password is php123. -->
</p>
</div>
</body>
