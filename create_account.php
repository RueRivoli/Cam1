<?php
if (!isset($_SESSION))
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
        <title>Camagru</title>
        <meta charset="utf-8">
        <meta name="description" content="165c. uniques">
        <link rel="stylesheet" type="text/css" href="stylesheet/essai2.css" media="all"/>
        <script type="text/javascript" src="js/check_correct_fields.js"></script>
</head>

<body>

<div class="sign-bar">
    <a href="index.php"><img src="img/photo1.png" alt="camera"></a><div id="welcome">Welcome</div>
</div>

<div class="content">
<form method="post" action="script/new_account.php" class="create_account">
<div id= "inscription">
    <img src="img/logo.png"  alt="homelogo">
    <div id = "accroche"><br/><br/>
    <h2>Create your account</h2>
    <?php if (isset($_SESSION['error']) && $_SESSION['error'] != "")
    {
        if ($_SESSION['error'] === "Vous avez reçu un mail de validation")
        {?>
            <h4><?php echo $_SESSION['error'];
            $_SESSION['error'] = "";
            ?></h4>
        <?php }
        else{
        ?>
        <h3><?php echo $_SESSION['error'];?></h3>
    <?php }}?>
    <label><b>Login</b></label><br/>
    <input id="login" placeholder="Login" type="text" name="uname" required onblur="verifLogin(this)"><br/>
    <label><b>Email</b></label><br/>
    <input id="email" placeholder="Email" type="text" name="email" required onblur="verifMail(this)"><br/>
    <label><b>Password</b></label><br/>
    <input class="password" id="pswd1" type="password" name="psw1" placeholder="Enter Password" name="psw1" required onblur="verifPswd(this)"><br/>
    <label><b>Confirm password</b></label><br/>
    <input class="password"  type="password" name="psw2" placeholder="Confirm Password" name="psw2" required onblur="confirmPswd(this)"><br/>
    <input id="submit" name="sub" type="submit"  value="Submit">
    </div>
    </div>
</div>
    </form>
<?PHP
    include 'script/connexion.php';
?>
</body>
</html>