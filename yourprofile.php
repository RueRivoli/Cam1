<?php
session_start();
if (!isset($_SESSION["filter"]))
    $_SESSION["filter"] = "none";
if (!isset($_SESSION["login"]) || $_SESSION["login"] == "")
{
    header('Location: index.php');
    exit;
}
if (isset($_SESSION['error']))
    $_SESSION['error'] = "";
?>

<!DOCTYPE HTML>
<html>

<head>
        <title>Camagru</title>
        <meta charset="utf-8">
        <meta name="description" content="165c. uniques">
        <link rel="stylesheet" type="text/css" href="stylesheet/gallery.css" media="all"/>
</head>

<body>

<?php
include 'functions/header.php';
$pseudo =$_SESSION['login'];
include 'config/database.php';
include "functions/initdb.php";
try {
    $st = $db->prepare('SELECT email FROM users WHERE login = ?');
    if($st->execute(array($pseudo)) && $row = $st->fetch())
        $mail = $row['email'];
}
catch(PDOException $e) {
    echo "Impossible to read the mail of the user ! The mistake is : ".$e;
}

?>
<div id="contener_profile">

    <div id="profile">
        <?php if (!isset($_SESSION['error']) || $_SESSION['error'] === "")
            echo "<h1>Your profile</h1>";
            else
            echo $_SESSION['error'];
        ?>
        <div class="criteria">
            <span>Pseudo: </span><?php echo $pseudo ?><br/>
        </div>
        <div class="criteria">
            <span>Email: </span><?php echo $mail ?>
        </div>
        <a class="stylebouton" href="modifyprofile.php">Modify it!</a>
            <!-- <button type="button">Modify it!</button> -->
    </div>
</div>

<footer>
@fgallois 2018
</footer>
</body>
</html>