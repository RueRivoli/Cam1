<?php
session_start();

function genere_password($size)
{
    // Initialisation des caractères utilisables
    $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

    for($i=0; $i<$size; $i++)
    {
        if ($i < $size - 2)
            $password .= ($i % 2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        else
        {
            $nb = rand (1 , 9);
            $password .= $nb;
        }
    }
    return $password;
}


function send_mail_for_change($p)
{
    include "../config/database.php";
    include "../functions/initdb.php";

    $code = hash("whirlpool", rand());
    $email = $p['email'];

    /*Find the login*/
    try{
        $st = $db->prepare("SELECT login FROM users WHERE email = :email ");
        if($st->execute(array(':email' => $email)) && $row = $st->fetch())
            $log = $row['login'];
    }
    catch(PDOException $e) {
        echo "Can't manage to select login! The mistake is : ".$e;
    }
      
    if ($log)
    {
        
        $rand = genere_password(8);
        
        try{
            /*Update password*/
            $st = $db->prepare("UPDATE users SET pswd = :new_psd WHERE login = :login ");
            $st->bindParam(':new_psd', $new_pswd);
            $st->bindParam(':login', $log);
            $new_pswd = hash("whirlpool", $rand);
            $st->execute();
    
            /*Update key of activity*/
            $st = $db->prepare("UPDATE users SET cle = :new_cle WHERE login = :login");
            $st->bindParam(':new_cle', $new_cle);
            $st->bindParam(':login', $log);
            $new_cle = $code;
            $st->execute();
        
     
            /*Update activity of the user*/
            $st = $db->prepare("UPDATE users SET activity = :new_acti WHERE login = :login");
            $st->bindParam(':new_acti', $new_acti);
            $st->bindParam(':login', $log);
            $new_acti = 0;
            $st->execute();
        }
        catch(PDOException $e) {
            echo "Can't manage to update password, activity or key! The mistake is : ".$e;
        }

            $subject = "Votre nouveau mot de passe" ;
            $entete = "From: inscription@camagru.com" ;
            $message = 'Bonjour'. $_SESSION['login'].',
    
            Il semblerait que vous ayez oublié votre mot de passe.

            Votre nouveau mot de passe est : '.$rand.' Vous pourrez le modifier une fois connecté.
            Pour vous activer votre compte et vous reconnecter, 
            cliquez sur le lien ci-dessus ou copier/coller les dans votre navigateur internet.

            http://localhost:8888/Camagru2/activation.php?log='.urlencode($log).'&cle='.urlencode($code).'
    
            ----------------------------------------------------------------------------------------
            Ceci est un mail automatique, Merci de ne pas y répondre.';
            mail($email, $subject, $message, $entete);
            $_SESSION['error'] = "Un email vous a ete envoyé";
            header('Location: ../index.php');
            exit;
        }
    else
    {
            $_SESSION['error'] = "Vous n'avez pas de compte";
            header('Location: ../index.php');
            exit;
    }
}


if ($_POST['sub'] === 'Submit')
    send_mail_for_change($_POST);

?>