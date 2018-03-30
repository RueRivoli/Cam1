<?php
session_start();
if (!isset($_SESSION["filter"]))
    $_SESSION["filter"] = "none";
if (!isset($_SESSION["login"]) || $_SESSION["login"] == "")
{
    header('Location: index.php');
    exit;
}
include 'functions/user_like.php';
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
include "functions/header.php";
?>

<div id="gallery">
<?php
include 'config/database.php';
include "functions/initdb.php";

$index = 1;
if (isset($_GET["page"]))
    $index = $_GET["page"];

try {
    
    $log = $_SESSION['login'];

    $st =  $db->prepare('SELECT COUNT(*) FROM post INNER JOIN users ON users.id_user = post.id_user WHERE users.user_login = :user_login');
    $st->bindParam(':user_login', $log);
    $st->execute();
    $tab = $st->fetch();

    $nb_pic = $tab['COUNT(*)'];
    $nb_pages = floor($nb_pic / 12);

    if ($nb_pic % 12 > 0)
         $nb_pages++;
     $offset = ($index - 1) * 12;

    $sql = $db->prepare("SELECT user_login, id_post, post_url, nb_likes, nb_comments, DATE_FORMAT(date_creation, '%d / %m') AS date_creation FROM post p INNER JOIN users u ON p.id_user = u.id_user WHERE u.user_login = :user_login ORDER BY id_post DESC LIMIT 12 OFFSET :offset");
    $sql->bindParam(':user_login', $log);
    $sql->bindParam(':offset', $offset, PDO::PARAM_INT);
    $sql->execute();
    $tab = $sql->fetchAll();

    $i = 0;
    while ($tab[$i])
    {
        ?>

    <div class="picture">
        <div class="entete">
        <span class="login"><?php echo $log ?></span>
        <span class="date_crea"><?php echo $tab[$i]['date_creation'] ?></span>
        </div>
        <div class="poster">
        <?php
                if (isset($_SESSION['login']) && $_SESSION['login'] !== "")
                {
                    echo "<a href=\"post.php?id=".$tab[$i]['id_post']."\">";
                    echo "<img src=\"".$tab[$i]['post_url']."\"></a>";
                }
                else
                    echo "<img src=\".$tab[$i]['post_url']\">";
            ?>
            
            <div class="foot2">
                <div id="heart">
                    <a href="script/like.php?post_id=<?php echo $tab[$i]['post_id']?>&amp;b=1">
                    <?php
                        if (if_user_like($tab[$i]['post_url'], $log) === 1)
                            echo "<img src=\"img/redlike.png\"></a>";
                        elseif (if_user_like($tab[$i]['post_url'], $log) === 0)
                            echo "<img src=\"img/like.png\"></a>";
                    ?>
                </div>
                <p id="nb_likes"><?php echo $tab[$i]['nb_likes']?></p>
                <div id="heart">
                    <img src="img/message.png">
                </div>
                <p id="nb_coms"><?php echo $tab[$i]['nb_comments']?></p>
                <span class="date_crea"><?php echo $tab[$i]['date_creation']?></span>
            </div>
        </div>
    </div>
     <?php
     $i++;
  }
}
catch(PDOException $e) {
    echo "Impossible to read in table post! The mistake is: ".$e;
}
?>

</div>

<div id="links">
<?php 
$i = 1;
while ($i < $nb_pages)
{
    echo "<a class = \"numbers\" href=\"gallery.php?page=".$i."\"><img src=\"img/".$i.".png\"></a>";
    $i++;
}
?>

</div>
<footer>
    @fgallois 2018
</footer>
</body>

</html>