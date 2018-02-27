<?php
    
    session_start();

    function insertIntoDatabase($p)
    {
        include "../config/database.php";
        include "../functions/initdb.php";
        try {
            $a = $db->prepare("INSERT INTO post (login, date_creation, post_id, nb_likes, nb_comments) VALUES (:name, NOW(), :cle, :nl, :nc)");
            $a->bindParam(':name', $name);
            $a->bindParam(':cle', $cle);
            $a->bindParam(':nl', $nl);
            $a->bindParam(':nc', $nc);
            $name = $_SESSION['login'];
            $cle = $p;
            $nl = 0;
            $nc = 0;
            $a->execute();
        }
        catch(PDOException $e) {
            echo "Impossible to insert the new post in the db! The mistake is : ".$e;
        }
    }

    $_format = array('image/jpeg', 'image/jpg', 'image/png');
    $_form = array('jpeg', 'jpg', 'png');

    if (empty($_FILES['fileToUpload']))
        $_SESSION['upload'] = 'fichier vide';
    else{
        $i = 0;
        $val = false;
        while ($i < 3)
        {
            if ($_FILES["fileToUpload"]['type'] === $_format[$i])
            {
                $val = true;
                $type = $_form[$i]; 
                break;
            }
            $i++;
        }
        
        $key = uniqid();
        //echo 'ressources/uploaded/'.$key.".".$type;
        if(isset($_POST["submit"]) && $val === true) {
            if ($_FILES['fileToUpload']['error'] > 0)
                $_SESSION['upload'] =  "Fichier non conforme";
            elseif ($_FILES['fileToUpload']['size'] > 4194304)
                $_SESSION['upload'] = 'fichier trop grand';
            elseif (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], '../ressources/uploaded/'.$key.".".$type))
                $_SESSION['upload'] = 'un probleme dans la creation';
            else
            {
               
                // $data = file_get_contents('../ressources/uploaded/'.$key.".".$type);
                //$destination = imagecreatefromstring($data);
                insertIntoDatabase('ressources/uploaded/'.$key.".".$type);
                $_SESSION['import'] = $data;
               
                header ('Location: ../principal.php');
                exit;
            }
        }
        else
            $_SESSION['upload'] = 'format inconnu';
    }
    header('Location: ../principal.php');
    exit;
?>