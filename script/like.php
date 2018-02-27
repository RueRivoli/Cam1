<?php
session_start();


/*If the user is log, we can add or remove a like: */
if (isset($_SESSION['login']) && $_SESSION['login'] != "")
{
    include "../config/database.php"; 
    include "../functions/initdb.php";
    try {
        $postid = htmlspecialchars($_GET['post_id']);
        $login = $_SESSION['login'];
        $sql = $db->prepare("SELECT post_id, login FROM likes WHERE post_id = ? AND login = ?");
        $sql->execute(array($postid, $login));
        $has_liked =  $sql->rowCount();
        if ($has_liked === 0)
        {
            $update = $db->prepare("INSERT INTO likes(post_id, login) VALUES(?,?)");
            $update->execute(array($postid, $login));

            $upd = $db->prepare("UPDATE post SET nb_likes = nb_likes + 1 WHERE post_id = ?");
            $upd->execute(array($postid));
        }
        else if ($has_liked >= 1)
        {
            $update = $db->prepare("UPDATE post SET nb_likes = nb_likes - 1 WHERE post_id = ?");
            $update->execute(array($postid));

            $upd = $db->prepare("DELETE FROM likes WHERE post_id = ? AND login = ?");
            $upd->execute(array($postid, $login));
        }
        
    }
    catch(PDOException $e) {
        echo "Impossible to modify the number of likes ! The mistake is : ".$e;
    }
}


if (htmlspecialchars($_GET['b']) == 0)
    header('Location: ../gallery.php');
elseif (htmlspecialchars($_GET['b']) == 1)
    header('Location: ../yourpictures.php');
elseif (htmlspecialchars($_GET['b']) == 2)
    header('Location: ../post.php?id='.$_GET['id']);
exit;
?>