<?php
if (!isset($_SESSION))
session_start();
if ($_GET['pseudo'] === '')
{
    header('Location: index.php');
    exit;
} 
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
<form method="post" action="script/create_new_pswd.php?login=".$_GET['pseudo'] class="create_account">
<div id= "inscription">
    <img src="img/logo.png"  alt="homelogo">
    <div id = "accroche"><br/><br/>
    <h2>An email will be sent to you. Mention it.</h2>
    <?php if (isset($_SESSION['error']) && $_SESSION['error'] != "")
    {
        if ($_SESSION['error'] === "You have received a mail of validation")
        {?>
            <h4><?php echo $_SESSION['error'];
            $_SESSION['error'] = "";
            ?></h4>
        <?php }
        else{
        ?>
        <h3><?php echo $_SESSION['error'];?></h3>
    <?php }}?>
    <label><b>Email</b></label><br/>
    <input id="email" placeholder="Email" type="text" name="email" required onblur="verifMail(this)"><br/>
    <input id="submit" name="sub" type="submit"  value="Submit">
    </div>
    </div>
</div>
    </form>
<?PHP
    include 'script/connexion.php';
?>
<footer>
@fgallois 2018
</footer>
</body>



</html>