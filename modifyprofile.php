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
        <link rel="stylesheet" type="text/css" href="stylesheet/gallery.css" media="all"/>
        <link rel="stylesheet" type="text/css" href="stylesheet/modifyprofile.css" media="all"/>
        <script type="text/javascript" src="js/check_correct_fields.js"></script>
    </head>

<body>

<?php



$pseudo = $_SESSION['login'];
include 'config/database.php';
include "functions/initdb.php";
try {
    $st = $db->prepare('SELECT email FROM users WHERE login = ?');
    if($st->execute(array($pseudo)) && $row = $st->fetch())
        $mail = $row['email'];
}
catch(PDOException $e) {
    echo "Impossible to have access to the mail 's user ! The mistake is : ".$e;
}

?>
<?php
include "functions/header.php";
?>
<div id="contener_profile">

    <div id="profile">
        <h1>Modify your account</h1>


        <form action="script/newprofile.php" method="post">
        <div id= "modalities">
            <span class="red"><?php echo $_SESSION['error']?></span>
            <div class="field">
                <span>Pseudo: </span><br>
                <span class="italique">Choose a new pseudo </span><br>
                <input type="text" name="pseudo"><br>
            </div>
            <div class="field">
            <p>Notifications :</p>
                <input class = "check" type="radio" name="Notif" value="Yes" checked>
                <label for="Yes">Oui aux notifications de commentaires</label><br>
                <input class = "check" type="radio" name="Notif" value="No">
                <label for="No">Non aux notifs</label><br> 
            </div>
            <div class="field">
                <span>Email: </span><br>
                <span class="italique">Choose a new email </span><br>
                <input type="text" name="email" >
            </div>
            <span>Password: </span><br>
            <div class="pass">
               
                <span class="italique">Write your current password</span><br>
                <input type="password" name="lastpsd">
            </div>
            <div class="pass">
                <span class="italique">Choose a new password</span><br>
                <input id ='pswd1' type="password" name="newpsd1">
            </div>
            <div class="pass">
                <span class="italique">Confirm the password</span><br>
                <input id ='pswd2' type="password" name="newpsd2">
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