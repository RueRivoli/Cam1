<?php
session_start();

function insertCom()
{
    include "../config/database.php";
    include "../functions/initdb.php";
    try {
        
        $a = $db->prepare("INSERT INTO comments (post_id, login, text, date_creation) VALUES (:postid, :login, :txt, NOW())");
        $a->bindParam(':postid', $postid);
        $a->bindParam(':login', $login);
        $a->bindParam(':txt', $txt);
        $login = $_SESSION['login'];
        $postid = $_SESSION['post'];
        $txt = htmlspecialchars($_POST['text']);
        $a->execute();

        $update = $db->prepare("UPDATE post SET nb_comments = nb_comments + 1 WHERE post_id = ?");
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
        $st = $db->prepare("SELECT login FROM post where post_id = ?");
        $st->execute(array($postid));
        $row = $st->fetch();
        $dest_user = $row['login'];

        $st = $db->prepare("SELECT notif FROM users WHERE login = :login ");
        $st->execute(array(':login' => $dest_user));
        $row = $st->fetch();
        $notif = $row['notif']; 

        
        if ($notif == 1)
        {
            $st = $db->prepare("SELECT email FROM users where login = ?");
            $st->execute(array($dest_user));
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