<?php
session_start();
if (!isset($_SESSION["filter"]))
    $_SESSION["filter"] = "none";
if (!isset($_SESSION["login"]) || $_SESSION["login"] == "")
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
        <link rel="stylesheet" type="text/css" href="stylesheet/modifyprofile.css" media="all"/>
        <script type="text/javascript" src="js/check_correct_fields.js"></script>
    </head>

<body>

<?php
include "functions/header.php";
?>

<?php

$pseudo = $_SESSION['login'];
include "config/database.php";
include "functions/initdb.php";
try {
    $st = $db->prepare('SELECT email FROM users WHERE user_login = ?');
    if($st->execute(array($pseudo)) && $row = $st->fetch())
        $mail = $row['email'];
}
catch(PDOException $e) {
    echo "Impossible to have access to the user's mail ! The mistake is : ".$e;
}

?>
<div id="contener_profile">

    <div id="profile">
        <h1>Your profile has been modified</h1>


        <form action="script/newprofile.php" method="post">
        <div id= "modalities">
            <span><?php echo $_SESSION['error']?></span>
            <div class="field">
                <span>Pseudo: </span><br>
                <span class="italique">Choose a new pseudo </span><br>
                <input type="text" name="pseudo"><br>
            </div>
            <div class="field">
                <span>Email: </span><br>
                <span class="italique">Choose a new email </span><br>
                <input type="text" name="email" >
            </div>
            <span>Password: </span><br>
            <div class="pass">
               
                <span class="italique">Write your current password</span><br>
                <input type="text" name="lastpsd">
            </div>
            <div class="pass">
                <span class="italique">Choose a new password</span><br>
                <input id ='pswd1' type="text" name="newpsd1">
            </div>
            <div class="pass">
                <span class="italique">Confirm the password</span><br>
                <input id ='pswd2' type="text" name="newpsd2">
            </div>
            <div id="sub">
                <input name='submit' type="submit" value="Submit">
            </div>
        </div>
        </form>
    </div>
</div>

<footer>

</footer>

</body>
</html>