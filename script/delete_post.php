<?php
session_start();

if (isset($_SESSION['login']))
{
    include "../config/database.php";
    include '../functions/initdb.php';

    try{

        if (isset($_GET['post_id']))
        {
            $pid = htmlspecialchars($_GET['post_id']);
            
            /*Verify that the post has been created by the user connected*/
            $st = $db->prepare("SELECT u.user_login FROM post p INNER JOIN users u ON u.id_user = p.id_user WHERE p.id_post = :idp");
            
            if($st->execute(array(':idp' => $pid)) && $row = $st->fetch())
                $log = $row['user_login'];

            /*If that's the case, let's remove the post*/
            if ($log == $_SESSION['login'])
            {
                
                $sql = "DELETE FROM post WHERE id_post = :idp";
                $st = $db->prepare($sql);
                $st->execute(array(':idp' => $pid));
            }
            else
            {
                header ('Location: ../post.php?id='.$pid);
                exit;
            }
        }
        elseif (isset($_GET['post_url']))
        {
            $purl = htmlspecialchars($_GET['post_url']);
            $st = $db->prepare("SELECT u.user_login FROM post p INNER JOIN users u ON u.id_user = p.id_user WHERE p.post_url = :purl");
            
            if($st->execute(array(':purl' => $purl)) && $row = $st->fetch())
                $log = $row['user_login'];

             /*If the owner of the post is the current user, let's remove the post*/
            if ($log == $_SESSION['login'])
            {
                $sql = "DELETE FROM post WHERE post_url = :purl";
                $st = $db->prepare($sql);
                $st->execute(array(':purl' => $purl));
            }
            else
            {
                header ('Location: ../post.php?purl='.$purl);
                exit;
            }
    }
}
    catch(PDOException $e) {
        echo "Impossible to check the post_id or to delete the post. The mistake is : ".$e;
    }
}
if ($_GET['b'] == 0)
    header ('Location: ../gallery.php');
elseif ($_GET['b'] == 1)
    header ('Location: ../principal.php');
exit;

?>