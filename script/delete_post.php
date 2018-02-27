<?php
session_start();


if (isset($_SESSION['login']))
{
    include "../config/database.php";
    include '../functions/initdb.php';

    try{
        /*Verify that the post has been created by the user connected*/
        $st = $db->prepare("SELECT id, login FROM post WHERE post_id = :pi ");
        if($st->execute(array(':pi' => htmlspecialchars($_GET['post_id']))) && $row = $st->fetch())
        {
            $log = $row['login'];
            $id = $row['id'];
        }

        /*If that's the case, let's remove the post*/
        if ($log == $_SESSION['login'])
        {
            $sql = "DELETE FROM post WHERE post_id = :pi";
            $st = $db->prepare($sql);
            $st->execute(array(':pi' => htmlspecialchars($_GET['post_id'])));
        }
        else
        {
            header ('Location: ../post.php?id='.$id);
            exit;
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