<?php
session_start();

function insertCom()
{
    include "../config/database.php";
    include "../functions/initdb.php";
    try {
        $login = $_SESSION['login'];
        $a = $db->prepare("SELECT id_user FROM users WHERE user_login = :user_login");
        $a->bindParam(':user_login', $login);
        $a->execute();
        $tab = $a->fetch();
        $id_user = $tab['id_user'];

        $a = $db->prepare("INSERT INTO comments (id_post, id_user, text, date_creation) VALUES (:postid, :id_user, :txt, NOW())");
        $a->bindParam(':postid', $postid);
        $a->bindParam(':id_user', $id_user);
        $a->bindParam(':txt', $txt);
        $id_user = $tab['id_user'];
        $postid = $_SESSION['post'];
        $txt = htmlspecialchars($_POST['text']);
        $a->execute();

        $update = $db->prepare("UPDATE post SET nb_comments = nb_comments + 1 WHERE id_post = ?");
        $update->execute(array($postid));
    }
    catch(PDOException $e) {
        echo "Impossible to insert the comment in the database! The mistake is : ".$e;
    }
}


function warn_for_comment()
{
    $login = $_SESSION['login'];
    $subject = "New message for you";
    $entete = "From: inscription@camagru.com";
    include "../config/database.php";
    include "../functions/initdb.php";

    try {

        $postid = $_SESSION['post'];
        $st = $db->prepare("SELECT id_user FROM post where id_post = ?");
        $st->execute(array($postid));
        $row = $st->fetch();
        $id_user = $row['id_user'];
   
        $st = $db->prepare("SELECT notif FROM users WHERE id_user= :id_user ");
        $st->execute(array(':id_user' => $id_user));
        $row = $st->fetch();
        $notif = $row['notif']; 

      
        if ($notif == 1)
        {
           
            $st = $db->prepare("SELECT email FROM users where id_user = ?");
            $st->execute(array($id_user));
            $row2 = $st->fetch();
            $dest = $row2['email'];
            
            $message = "Salut c'est Camagru,    

            Votre post a reçu un commentaire !

            En effet, "
            .$login." a réagi à votre photo : ".htmlspecialchars($_POST['text'])."\n"."
            A bientôt pour de nouvelles aventures.\n
            ----------------------------------------------------------------------------------------
            Ceci est un mail automatique, Merci de ne pas y répondre";
            $test = mail($dest, $subject, $message, $entete);
        }
    }
    catch(PDOException $e) {
        echo "Impossible to send the mail : ".$e;
    }
}

insertCom();
warn_for_comment();
header('Location: ../post.php?id='.$_SESSION['img_id']);
exit;
?>