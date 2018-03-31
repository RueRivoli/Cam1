<?php 

function if_user_like($postid, $login){
    include 'config/database.php';
    include 'functions/initdb.php';
    
    try {

        $sql = $db->prepare("SELECT COUNT(*) FROM likes l INNER JOIN users u ON l.id_user = u.id_user WHERE l.id_post = ? AND u.user_login = ?");
        $sql->execute(array($postid, $login));
        $tab = $sql->fetch();
        $has_liked = $tab['COUNT(*)'];
        if ($has_liked == 0)
            return (0);
         else if ($has_liked == 1)
            return (1);
    }
    catch(PDOException $e) {
         echo "Impossible to have access to table likes! The mistake is : ".$e;
    }
    return (0);
}
?>