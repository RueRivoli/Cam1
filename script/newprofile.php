<?php
session_start();

require "../functions/account.php";

function loginexist($p)
{
    include "../config/database.php";
    include "../functions/initdb.php";
    try {
       
        $sql = $db->prepare("SELECT id_user FROM users WHERE user_login = ?");
        $sql->execute(array($p));
        $nb_login = $sql->rowCount();
        
        if ($nb_login != 0)
            return (0);
        return (1);
    }
    catch(PDOException $e) {
        echo "Impossible to know if the login exists. The mistake is : ".$e;
    }
}

function login_pswd_associated($p)
{
    $login = $_SESSION['login'];
    $psd = hash('whirlpool', $p);
    $activ = 1;
    include "../config/database.php";
    include "../functions/initdb.php";
    try {
  
        $sql = $db->prepare("SELECT user_login, pswd FROM users WHERE user_login=:logi AND pswd = :pd AND activity = :a");
        $sql->bindParam(':logi', $login);
        $sql->bindParam(':pd', $psd);
        $sql->bindParam(':a', $activ);
        $sql->execute();
        $occur = $sql->rowCount();
        if ($occur !== 1)
            return (0);
        return (1);
    }
    catch(PDOException $e) {
        echo "Impossible to know if login and pswd are correct. The mistake is :".$e;
    }
}

function correct_formula_pseudo($p){
    
    if (loginexist($p) != 1)
        $_SESSION['error'] = 'This pseudo already exists';
    else if (strlen($p) < 3 || strlen($p) > 15 )
     $_SESSION['error'] = 'The pseudo must contain between 3 and 15 characters';
    if ($_SESSION['error'] === "")
        return (1);
    return (0);
}

function correct_formula_email($p){
    if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $p) != 1)
    {
        $_SESSION['error'] = 'Your mail is incorrect';
        return (0);
    }
    return (1);
}

function correct_pswd($p, $q){
    if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#', $p) != 1 || strlen($p) > 15)
        $_SESSION['error'] = 'The password must contain at least 1 capital letter, 1 lower case letter and 1 figure. It must be between 6 and 15 caracters';
    else if ($p !== $q)
        $_SESSION['error'] = 'Your passwords are not similar';
    else
        return (1);
    return (0);
}

function correct_formula_psd($p, $q1, $q2){
   if (login_pswd_associated($p) === 0)
    {
        $_SESSION['error'] = "Your current password is incorrect";
        return (0);
    }
   else if (correct_pswd($q1, $q2) === 0)
        return (0);
    return (1);
}

function insert_new_mail($mail, $log){
    include "../config/database.php";
    include "../functions/initdb.php";
    try {
        $st = $db->prepare("UPDATE users SET email = ? WHERE user_login = ? ");
        $st->execute(array($mail, $log));
        $st = $db->prepare("UPDATE users SET activity= ? WHERE user_login = ? ");
        $st->execute(array(0, $log));
    }
    catch(PDOException $e) {
        echo "Impossible to insert new mail in the database. The mistake is :".$e;
    }
}

function send_mail_reactivate($log, $mail)
{
    include "../config/database.php";
    include "../functions/initdb.php";
    try {
        $a = $db->prepare("UPDATE users SET cle= :cle WHERE user_login = :user_login");
        $a->bindParam(':cle', $code);
        $a->bindParam(':user_login', $log);
        $code = hash("whirlpool", rand());
        $a->execute();
    }
    catch(PDOException $e) {
        echo "Impossible to update the key of login".$e;
}
    $dest = $mail;

    $link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $link = str_replace("/script/newprofile.php", "", $link);
    $link = $link . '/activation.php?log='.urlencode($log).'&cle='.urlencode($code);
     
    $subject = "Activer votre nouvelle adresse-mail" ;
    $entete = "From: inscription@camagru.com" ;
    $message = 'Bienvenue sur Camagru,
    
   Pour activer votre nouvelle adresse mail, veuillez cliquer sur le lien ci-dessous
   ou copier/coller dans votre navigateur internet.'

   .$link.'
    
   ----------------------------------------------------------------------------------------
   Ceci est un mail automatique, Merci de ne pas y répondre.';
    mail($dest, $subject, $message, $entete) ;
    $_SESSION['error'] = "A mail has been sent to your mailbox";
}

function insert_new_psd($psd)
{
    include "../config/database.php";
    include "../functions/initdb.php";
    
    try {
        $a = $db->prepare("UPDATE users SET pswd= :psd WHERE user_login = :user_login");
        $a->bindParam(':psd', $pswd);
        $a->bindParam(':user_login', $_SESSION['login']);
        $pswd = hash("whirlpool", $psd);
        $a->execute();
    }
    catch(PDOException $e) {
        echo "Impossible to insert new psd".$e;
    }
}

function set($p)
{
    if (isset($p) && htmlspecialchars($p) != "")
        return true;
    return false;
}

if ($_SESSION['login'] && htmlspecialchars($_POST['submit']) === 'Submit')
{
   
    $_SESSION['error'] = "";
    if (set($_POST['pseudo']))
    {
        
        if (correct_formula_pseudo($_POST['pseudo']) === 0)
        {
            header('Location: ../modifyprofile.php');
            //exit;
        }
        
    }
    if (set($_POST['email']))
    {
        if (correct_formula_email(htmlspecialchars($_POST['email'])) === 0)
        {
            header('Location: ../modifyprofile.php');
            //exit;
        }
    }
    if (set($_POST['lastpsd']) && set($_POST['newpsd1']) && set($_POST['newpsd2']))
    {
        if (correct_formula_psd(htmlspecialchars($_POST['lastpsd']), htmlspecialchars($_POST['newpsd1']), htmlspecialchars($_POST['newpsd2'])) === 0)
        {
            header('Location: ../modifyprofile.php');
            //exit;
        }
    }
    elseif (!(!set($_POST['lastpsd']) && !set($_POST['newpsd1']) && !set($_POST['newpsd2'])))
    {
        $_SESSION['error'] = "Please fill all the passwords areas";
        header('Location: ../modifyprofile.php');
        //exit;
    }
    include "../config/database.php";
    include "../functions/initdb.php";
    if (set($_POST['pseudo']))
    {
        try {
            $st = $db->prepare("UPDATE users SET user_login = ? WHERE user_login = ?");
            $st->execute(array(htmlspecialchars($_POST['pseudo']), $_SESSION['login']));
            $_SESSION['login'] = htmlspecialchars($_POST['pseudo']);
        }
        catch(PDOException $e) {
            echo "Impossible to get the login".$e;
        }
    }
    //Update notifications
    if (isset($_POST['Notif']))
    {   
        $not = 1;
        if ($_POST['Notif'] === 'No')
            $not = 0;
        try {
            $st = $db->prepare("UPDATE users SET notif = :noti WHERE user_login = :log ");
            $st->bindParam(':noti', $not);
            $st->bindParam(':log', $log);
            $log = $_SESSION['login'];
            $st->execute();
        }
        catch(PDOException $e) {
            echo "Impossible to set the notifications".$e;
        }
    }
    if (set($_POST['email']))
    {
        insert_new_mail(htmlspecialchars($_POST['email']), $_SESSION['login']);
        send_mail_reactivate($_SESSION['login'], htmlspecialchars($_POST['email']));
        header('Location: ../index.php');
        $_SESSION['error'] = "Activate your account in your mailbox"; 
        //exit;
    }
    if (set($_POST['lastpsd']) && set($_POST['newpsd1']) && set($_POST['newpsd2']))
        insert_new_psd(htmlspecialchars($_POST['newpsd1']));
  
    header('Location: ../yourprofile.php');
    //exit;
}
header('Location: ../yourprofile.php');
exit;

?>