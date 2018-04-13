<?php 

$login = htmlspecialchars($_GET['log']);
$cle = htmlspecialchars($_GET['cle']);
 
// Récupération de la clé correspondant au $login dans la base de données
include "config/database.php";
include "functions/initdb.php";

try{
    $st = $db->prepare("SELECT cle,activity FROM users WHERE user_login = :login ");
    if($st->execute(array(':login' => $login)) && $row = $st->fetch())
    {
        $cleb = $row['cle'];	// On récupération de la clé
        $activity = $row['activity']; // $actif contiendra alors 0 ou 1
    }
    if($activity === '1') // Si le compte est déjà actif on prévient
    {   
        $_SESSION['login'] = $login;
        header('Location: principal.php');
        exit;
    }
    else // Si ce n'est pas le cas on passe aux comparaisons
    {
        if($cle === $cleb) // On compare nos deux clés	
        {
            // Si elles correspondent on active le compte !
            // La requête qui va passer notre champ actif de 0 à 1
            $st = $db->prepare("UPDATE users SET activity = 1 WHERE user_login = :login ");
            $st->bindParam(':login', $login);
            $login = htmlspecialchars($_GET['log']);
            $st->execute();
            $_SESSION['login'] = $login;
            $_SESSION['error'] = "Connectez-vous !";
            header('Location: index.php');
            exit;
        }
        else
            echo "Erreur ! Votre compte ne peut être activé...";
    }
}
catch(PDOException $e) {
    echo "Can't manage to active the count in the database! The mistake is : ".$e;
}
?>