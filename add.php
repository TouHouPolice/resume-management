<?php
  session_start();
  require_once "pdo.php";
  require_once "utility.php";

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

  if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){

    if(!(strlen($_POST['first_name'])>0 && strlen($_POST['last_name'])>0 && strlen($_POST['email'])>0 && strlen($_POST['headline'])>0 && strlen($_POST['summary'])>0)){
      $_SESSION['error']="All fields are required";
    }
    elseif (strpos($_POST['email'],"@")==false){
      $_SESSION['error']="Email address must contain @";
    }
    elseif(validatePos()!==true){
      $_SESSION['error']=validatePos();
    }
    else{  //Proceed to insert
      $sql = "INSERT INTO profile (user_id,first_name,last_name,email,headline,summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary']));

      $profile_id=$pdo->lastInsertId();
      $rank=0;
      for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;

        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        $sql='INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)';
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
        ':pid' => $profile_id,
        ':rank' => $rank,
        ':year' => $year,
        ':desc' => $desc));
        $rank+=1;
      }
    //$success=true;
    $_SESSION['success']="Profile added";
    header("Location: index.php");
    return;
    }
    header("Location: add.php");
    return;
  }



function validatePos() {
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    if ( strlen($year) == 0 || strlen($desc) == 0 ) {
      return "All fields are required";
    }

    if ( ! is_numeric($year) ) {
      return "Position year must be numeric";
    }
  }
  return true;
}



?>





<html>
<head>
<title>Registry</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<?php require_once "libs.php"; ?>

</head>
<body>
<? require_once './components/layout.php' ?>
<div class="container">
<h1>Adding Profile for <? echo htmlentities($_SESSION['name']); ?></h1>

<?php
if ($failure){
  echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>

<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">

</div>
</p>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>

<script>
countPos=0;
$(document).ready(function(){
  window.console && console.log('Document ready called');
  $('#addPos').click(function(event){
    event.preventDefault();
    if(countPos>=9){
      alert('Maxium of nine position entries exceeded');
      return;
    }
    countPos++;
    console.log("Adding position "+countPos);
    $('#position_fields').append('<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
  });
});
</script>

</html>
