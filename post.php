
<?php
session_start();


if (!isset($_SESSION["filter"]))
    $_SESSION["filter"] = "none";

if (!isset($_SESSION["login"]) || $_SESSION["login"] == "")
{
    header('Location: index.php');
    exit;
}

include 'config/database.php';
include 'functions/initdb.php';
include 'functions/user_like.php';

try{
    if (isset($_GET['id']))
    {
        $idp = htmlspecialchars($_GET['id']);
        $sql = $db->prepare("SELECT user_login, u.id_user,post_url, nb_likes, nb_comments, DATE_FORMAT(date_creation, '%d / %m') AS date_creation FROM post p INNER JOIN users u ON p.id_user = u.id_user WHERE p.id_post = :id_post");
        $sql->bindParam(':id_post', $idp);
        $sql->execute();
        if ($line = $sql->fetch(PDO::FETCH_ASSOC))
        {
            $id_user = $line['id_user'];
            $date_creation = $line['date_creation'];
            $post_url = $line['post_url'];
            $nb_likes = $line['nb_likes'];
             $nb_comments = $line['nb_comments'];
            $log = $line['user_login'];
        }
        $_SESSION['id_post'] = $idp;
        
    }
    else if (isset($_GET['purl']))
    {
        $purl = htmlspecialchars($_GET['purl']);
        $sql = $db->prepare("SELECT user_login, u.id_user, p.id_post, nb_likes, nb_comments, DATE_FORMAT(date_creation, '%d / %m') AS date_creation FROM post p INNER JOIN users u ON p.id_user = u.id_user WHERE p.post_url = :post_url");
        $sql->bindParam(':post_url', $purl);
        $sql->execute();
        if ($line = $sql->fetch(PDO::FETCH_ASSOC))
        {
            $id_user = $line['id_user'];
            $date_creation = $line['date_creation'];
            $idp = $line['id_post'];
            $nb_likes = $line['nb_likes'];
            $nb_comments = $line['nb_comments'];
            $log = $line['user_login'];
        }
        $_SESSION['id_post'] = $idp;
      
    }
}
catch(PDOException $e) {
    echo "Can't have access to table post! The mistake is : ".$e;
}



?>

<!DOCTYPE HTML>
<html>

<head>
        <title>Camagru</title>
        <meta charset="utf-8">
        <meta name="description" content="165c. uniques">
        <link rel="stylesheet" type="text/css" href="stylesheet/gallery.css" media="all"/>
        <link rel="stylesheet" type="text/css" href="stylesheet/post.css" media="all"/>
        <script type="text/javascript" src="js/comments.js"></script>
</head>

<body>

<?php
include "functions/header.php";
?>

<div id="square">

<div id="pic">
    <div class="entete2">
        <span class="title"><?php echo $log?></span>
        <span class="date"><?php echo $date_creation?></span>
    </div>

    <div class="shot">
    <img class="capture" src= <?php echo $post_url?>>
    </div>
    <div class="foot">
        <div id="heart">
        <a href="script/like.php?post_id=<?php echo $idp?>&amp;b=2">
        <?php
        $val = if_user_like($idp, $_SESSION['login']);
            if ($val >= 1)
                echo "<img src=\"img/redlike.png\"></a>";
            else
                echo "<img src=\"img/like.png\"></a>";
        ?>
        </div>
        <?php
            if (if_user_like($idp, $_SESSION['login']) === 1)
                echo "<p id=\"nb_likes_red\">";
            else
                echo "<p id=\"nb_likes\">";
            echo $nb_likes?></p>
            <div id="heart">
                <img src="img/message.png">
            </div>
            <p id="nb_coms"><?php echo $nb_comments?></p>
            <div id="trash">
                <a href="script/delete_post.php?post_id=<?php echo $idp?>&amp;b=0"><img src="img/trash2.png"></a>
            </div>
    </div>


     <div id= "com" class="comments">
        <div id="texts">
     <?php
        include "functions/initdb.php";
        try{
            
            $req = $db->prepare("SELECT c.id_user, u.user_login, c.text, c.date_creation FROM comments c INNER JOIN users u ON c.id_user = u.id_user WHERE c.id_post = ? ORDER BY date_creation ASC");
            if ($req->execute(array($idp)) && $tab = $req->fetchAll())
            {
                $i = 0;
                while ($tab[$i])
                {
                    echo "<div class=\"one_com\">";
                    echo "<div class=\"author\"><div class=\"namelog\">".$tab[$i]['user_login']."</div></div>";
                    echo "<div class=\"mess\"><div id=\"writing\">".$tab[$i]['text']."</div></div>";
                    echo "</div>";
                    $i++;
                }
                echo "</div>";
            }
        }
                catch(PDOException $e) {
                    echo "Impossible d'inserer le motif! L'erreur est : ".$e;
                }
                
        ?>
        
        <div id= "form_com">
            <div id="write_com">
                <div id="send">
                <input id="sub" type="submit" value="Publish" onClick="saveComment(<?php echo $_SESSION['id_post']?>);">
                </div>
            <input id="valuecom" type="text" name="text" placeholder="Add a comment...">
            </div>
        </div>
</div>
</div>
</div>
<footer>
@fgallois 2018
</footer>
</body>
</html>