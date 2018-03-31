<?php
session_start();

/*If the user is log, we can add or remove a like: */
if (isset($_SESSION['login']) && $_SESSION['login'] != "")
{
    
    include "../config/database.php"; 
    include "../functions/initdb.php";
    include "../functions/user_like.php";
    try {
        $postid = htmlspecialchars($_GET['post_id']);
        $login = $_SESSION['login'];
        $req = $db->prepare("SELECT id_user FROM users WHERE user_login = ?");
        $req->execute(array($login));
        $tab = $req->fetch();
        $id_user = $tab['id_user'];
  
        /*Avoid to repeat*/
        $sql = $db->prepare("SELECT COUNT(*) FROM likes l INNER JOIN users u ON l.id_user = u.id_user WHERE l.id_post = ? AND u.user_login = ?");
        $sql->execute(array($postid, $login));
        $tab = $sql->fetch();
        $has_liked = $tab['COUNT(*)'];

        if ($has_liked == 0)
        {
            $update = $db->prepare("INSERT INTO likes(id_post, id_user) VALUES (?,?)");
            $update->execute(array($postid, $id_user));

            $upd = $db->prepare("UPDATE post SET nb_likes = nb_likes + 1 WHERE id_post = ?");
            $upd->execute(array($postid));
        }
        else
        {
            $update = $db->prepare("UPDATE post SET nb_likes = nb_likes - 1 WHERE id_post = ?");
            $update->execute(array($postid));

            $upd = $db->prepare("DELETE FROM likes WHERE id_post = ? AND id_user = ?");
            $upd->execute(array($postid, $id_user));
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
    header('Location: ../post.php?id='.$_GET['post_id']);
exit;
?>