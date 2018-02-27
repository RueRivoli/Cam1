<?php 
session_start();
include "../functions/account.php";

if ($_POST['sub'] === 'Submit')
{
    if (correct_formula($_POST) === 1)
    {
        $pwd = hash('whirlpool', htmlspecialchars($_POST['psw1']));
        include "../config/database.php";
        include "../functions/initdb.php";
        try{
            $st = $db->prepare("SELECT login FROM users WHERE login = :login");
            if($st->execute(array(':login' => htmlspecialchars($_POST['uname']))))
                $nb_count=  $st->rowCount();
            if ($nb_count === 0)
                create_account($_POST, $pwd);
            else
                $_SESSION['error'] = 'Vous avez deja un compte. Connectez-vous';
        }
        catch(PDOException $e) {
            echo "Can't manage to select the login! The mistake is : ".$e;
        }
       
    }
        header('Location: ../create_account.php');
        exit;
}

?>