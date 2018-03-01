<?php
session_start();
if (!isset($_SESSION["filter"]))
    $_SESSION["filter"] = "none";
$index = 1;
if (isset($_GET["page"]))
    $index = $_GET["page"];
if (isset($_SESSION['error']))
    $_SESSION['error'] = "";
include "functions/user_like.php";
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
try {
    $st =  $db->prepare('SELECT COUNT(*) FROM post ');
    $st->execute();
    $tab = $st->fetchAll();
    $nb_pic = $tab[0]['COUNT(*)'];
    $nb_pages = $nb_pic / 12;
    if ($nb_pages % 12 > 0)
        $nb_pages++;
    $offset = ($index - 1) * 12 + 1;
    $sql = $db->prepare("SELECT id, login, post_id, nb_likes, nb_comments, DATE_FORMAT(date_creation, '%d / %m') AS date_creation FROM post ORDER BY id DESC LIMIT 12 OFFSET :offset");
    $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
    $sql->execute();
    $tab = $sql->fetchAll();
    $i = 0;
    while ($tab[$i])
    {
        ?>

    <div class="picture">
        <div class="entete">
        <span class="login"><?php echo $tab[$i]['login']?></span>
        
        </div>
        <div class="poster">
            <?php 
                if (isset($_SESSION['login']) && $_SESSION['login'] !== "")
                {
                    echo "<a href=\"post.php?id=".$tab[$i]['id']."\">";
                    echo "<img src=\"".$tab[$i]['post_id']."\"></a>";
                }
                else
                    echo "<img src=\"".$tab[$i]['post_id']."\">";
            ?>
            
            <div class="foot2">
                <div id="heart">
                    <a href="script/like.php?post_id=<?php echo $tab[$i]['post_id']?>&amp;b=0">
                <?php
                    if (if_user_like($tab[$i]['post_id'], $_SESSION['login']) === 1)
                        echo "<img src=\"img/redlike.png\"></a>";
                    else
                        echo "<img src=\"img/like.png\"></a>";
                ?>
                </div>
                <?php
                    if (if_user_like($tab[$i]['post_id'], $_SESSION['login']) === 1)
                        echo "<p id=\"nb_likes_red\">";
                    else
                        echo "<p id=\"nb_likes\">";
                ?>
                <?php echo $tab[$i]['nb_likes']?></p>
                <div id="heart">
                    <img src="img/message.png">
                </div>
                <p id="nb_coms"><?php   echo "   ".$tab[$i]['nb_comments']?></p>
                <span class="date_crea"><?php echo $tab[$i]['date_creation']?></span>
            </div>
        </div>
    </div>
     <?php
     $i++;
            }
}
catch(PDOException $e) {
    echo "Impossible to display the post! The mistake is : ".$e;
}
?>

<?php 
    $i = 0;
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