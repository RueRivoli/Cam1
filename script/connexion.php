<?php 
session_start();

function activ_user()
{
    include '../config/database.php';
    include "../functions/initdb.php";
    try{
        $req = $db->prepare("SELECT login, activity FROM users WHERE login= :login");
        $req->execute(Array('login' => htmlspecialchars($_POST['pseudo'])));
    
        if ($line = $req->fetch(PDO::FETCH_ASSOC))
        {
            if ($line['activity'] === '1')
                return TRUE;
        }
    }
    catch(PDOException $e) {
        echo "Can't check the activity of the user! The mistake is : ".$e;
    }
        return FALSE;
}


if ($_POST['logIN'] === 'log in')
{
    include '../config/database.php';
    include "../functions/initdb.php";
    $psd = hash('whirlpool', htmlspecialchars($_POST['pswd']));
    try{
        $st = $db->prepare("SELECT pswd FROM users WHERE login = :login");
        
        if ($st->execute(array(':login' => $_POST['pseudo'])) && $row = $st->fetch())
        {
            if (activ_user()  && $row['pswd'] == $psd)
            {
                $_SESSION['login'] = htmlspecialchars($_POST['pseudo']);
                header('Location: ../principal.php');
                exit;
            }
            else if (!activ_user())
                $_SESSION['error'] = "Please activate your account in your mailbox";
        }
        else 
            $_SESSION['error'] = "You don't have any account";
    }
    catch(PDOException $e) {
        echo "Impossible to have access to the users table! The mistake is : ".$e;
    }
   
    header('Location: ../index.php');
    exit;
}

?>