<?php
session_start();
$_SESSION['login'] = "";
$_SESSION['id_post'] = 0;
include 'config/setup.php';

?>

<!DOCTYPE HTML>
<html>

<head>
        <title>Camagru</title>
        <meta charset="utf-8">
        <meta name="description" content="165c. uniques">
        <link rel="stylesheet" type="text/css" href="stylesheet/essai.css" media="all"/>
</head>

<body>

<div class="sign-bar">
    <a href="gallery.php"><img src="img/photo1.png" alt="camera"></a>
    <div id="welcome"><?php if ( !isset($_SESSION['error']) || $_SESSION['error'] == "") {echo "Welcome";} else { echo $_SESSION['error'];
    $_SESSION['error'] = "";} ?></div>
    <form method="post" action="script/connexion.php" id="log">
    Login<br/><input type="text" name="pseudo" /><br/><br/>
    Password<br/><input type="password" name="pswd" /><br/>
    <!-- <div id="link"><a href="forbiddenpswd.php" >Forgot your password?</a><br/></div> -->
    <div id="link"><input type="submit" name="forget" value="Forgot your password?"></a><br/></div> 
    <input type="submit" name="logIN" value="log in" id="enter"/>
    </form>
</div>

<div class="content">
    <form method="post" action="create_account.php" class="create_account">
    <div id= "inscription">
        <img src="img/logo.png" alt="homelogo">
    <div id = "accroche">
    <h1>L'appli insta du moment</h1>
    <h3>Choisi les meilleurs accessoires</h3>
    <div id="abonne">
    <input type="submit" name="log" value="suscribe"/>
    </div>
</div>
    </div>
</div>
</form>
<?PHP
    include 'script/connexion.php';
?>

</body>

</html>