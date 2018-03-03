<?php
session_start();


function correct_formula($p){
    $_SESSION['error'] = "";
    if (strlen($p['uname']) < 3 || strlen($p['uname']) > 15 )
        $_SESSION['error'] = 'Le login doit contenir entre 3 et 15 caracteres';
    else if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $p['email']) != 1)
        $_SESSION['error'] = 'L\' adresse mail est incorrecte';
    else if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#', $p['psw1']) != 1 || strlen($p['psw1']) > 15)
        $_SESSION['error'] = 'Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule et 1 chiffre. Il doit faire entre 6 et 15 caracteres';
    else if ($p['psw1'] !== $p['psw2'])
        $_SESSION['error'] = 'Vos mots de passe ne correspondent pas';
    if ($_SESSION['error'] != "")
     {
        header('Location: ../create_account.php');
        exit;
     }
     return (1);
}

function send_mail($p)
{
    include "../config/database.php";
    include "initdb.php";

    try{
        $a = $db->prepare("UPDATE users SET cle= :cle WHERE login = :login");
        $a->bindParam(':cle', $code);
        $a->bindParam(':login', $log);
        $code = hash("whirlpool", rand());
        $log = $p['uname'];
        $a->execute();
    }
    catch(PDOException $e) {
        echo "Impossible to update the key! The mistake is : ".$e;
    }

    $dest = $p['email'];
    $subject = "Activer votre compte";
    $entete = "From: inscription@camagru.com";
    $message = 'Bienvenu sur Camagru,
    
   Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
   ou copier/coller dans votre navigateur internet.

   http://localhost:8081/Camagru2/activation.php?log='.urlencode($log).'&cle='.urlencode($code).'
    
   ----------------------------------------------------------------------------------------
   Ceci est un mail automatique, Merci de ne pas y répondre.';
    try{
        $test = mail($dest, $subject, $message, $entete);
    }
    catch(PDOException $e) {
        echo "Pb on sending mails! The mistake is : ".$e;
    }
    $_SESSION['error'] = "Un email vous a ete envoyé";
    header('Location: ../index.php');
    exit;
}

function create_account($p, $pw){
    include "../config/database.php";
    include "initdb.php";
    try {
        
        $a = $db->prepare("INSERT INTO users (login, email, pswd, cle, activity) VALUES (:name, :value, :pswd, :cle, :activi)");
        $a->bindParam(':name', $name);
        $a->bindParam(':value', $value);
        $a->bindParam(':pswd', $pd);
        $a->bindParam(':cle', $cle);
        $a->bindParam(':activi', $activi);
        $name = $p['uname'];
        $value = $p['email'];
        $pd = $pw;
        $cle = "";
        $activi = 0;
       
        $a->execute();
        send_mail($_POST);
    }
    catch(PDOException $e) {
        echo "Impossible to create account! The mistake is : ".$e;
    }
}

?>