<?php
    
    session_start();

    function insertIntoDatabase($p)
    {
        include "../config/database.php";
        include "../functions/initdb.php";
        try {
            $a = $db->prepare("SELECT id_user FROM users where user_login =:userl");
            $a->bindParam(':userl', $_SESSION['login']);
            $a->execute();
            $tab = $a->fetch();

            $a = $db->prepare("INSERT INTO post (id_user, date_creation, post_url, nb_likes, nb_comments) VALUES (:idu, NOW(), :purl, :nl, :nc)");
            $a->bindParam(':idu', $idu);
            $a->bindParam(':purl', $cle);
            $a->bindParam(':nl', $nl);
            $a->bindParam(':nc', $nc);
            $idu = $tab['id_user'];
            $cle = $p;
            $nl = 0;
            $nc = 0;
            $a->execute();
        }
        catch(PDOException $e) {
            echo "Impossible to insert the new post in the db! The mistake is : ".$e;
        }
    }

    function createmontage($p, $type, $fil)
    {
        if ($type === 'jpeg' || $type === 'jpg')
             $photo_cam = imagecreatefromjpeg($p);
        else if ($type === 'png')
            $photo_cam = imagecreatefrompng($p);
        $filt = imagecreatefrompng('../img/'.$fil.'.png');
        $filter_w = imagesx($filt);
        $filter_h = imagesy($filt);
        $name = array('dolphin', 'lion', 'noel', 'asmshirt', 'asmlogo', 'cadre', 'cadre2', 'scarlett', 'collieraclous', 'kungfu', 'mrbean');
        $w = array('320', '200', '200', '350', '130', '400', '420', '200', '160', '170', '300');
        $h = array('320', '200', '200', '350', '130', '400', '420', '200', '160', '170', '200');
        $pos_x = array('220', '0', '120', '50', '20', '0', '0', '200', '130', '10', '0');
        $pos_y = array('10', '140', '0', '100','100', '0', '0', '100', '140', '130', '100');

        $i = 0;
        while ($name[$i])
        {
            if ($_SESSION['filter'] === $name[$i])
            {
                $value_w = $w[$i];
                $value_h = $h[$i];
                $dst_x = $pos_x[$i];
                $dst_y = $pos_y[$i];
                break;
            }
            $i++;
        }

        $filter = imagecreatetruecolor($value_w, $value_h);
        imagealphablending($filter, false);
        imagesavealpha($filter, true);
        imagecopyresampled($filter, $filt, 0, 0, 0, 0, $value_w, $value_h, $filter_w, $filter_h);

        $photo_x = imagesx($photo_cam) - imagesx($filter);
        $photo_y = imagesy($photo_cam) - imagesy($filter);

        //imageflip($photo_cam, IMG_FLIP_HORIZONTAL);
        $uiid = uniqid();
        imagecopyresampled($photo_cam, $filter, $dst_x, $dst_y, 0, 0, $value_w, $value_h, $value_w, $value_h);
        $p = '../photos/'. $uiid . '.png';
        file_put_contents($p, $photo_cam);
        $sucess = imagepng($photo_cam, $p);
        $p = 'photos/'. $uiid . '.png'; 
        insertIntoDatabase($p);
        //echo $p;
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

        if(isset($_POST["submit"]) && $val === true) {
            if ($_FILES['fileToUpload']['error'] > 0)
                $_SESSION['upload'] =  "Fichier non conforme";
            elseif ($_FILES['fileToUpload']['size'] > 4194304)
                $_SESSION['upload'] = 'fichier trop grand';
            elseif (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], '../ressources/uploaded/'.$key.".".$type))
                $_SESSION['upload'] = 'un probleme dans la creation';
            else
            {
                $_SESSION['import'] = $data;
                $fil = (isset($_SESSION["filter"])) ? $_SESSION["filter"] : "lion";
                createmontage('../ressources/uploaded/'.$key.".".$type, $type, $fil);
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