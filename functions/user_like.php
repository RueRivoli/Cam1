<?php 

function if_user_like($postid, $login){
    include 'config/database.php';
    include 'functions/initdb.php';
    try {
        $sql = $db->prepare("SELECT id, post_id, login FROM likes WHERE post_id = ? AND login = ?");
        $sql->execute(array($postid, $login));
        $has_liked =  $sql->rowCount();
        if ($has_liked === 0)
            return 0;
        else if ($has_liked === 1)
            return (1);
    }
    catch(PDOException $e) {
         echo "Impossible to have access to table likes! The mistake is : ".$e;
    }
    return (0);
}
?>