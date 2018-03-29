<?php
session_start();
header("Content-Type: text/plain"); // Utilisation d'un header pour spécifier le type de contenu de la page. Ici, il s'agit juste de texte brut (text/plain).
$data = (isset($_POST["data"])) ? htmlspecialchars($_POST["data"]) : NULL;
$fil = (isset($_SESSION["filter"])) ? $_SESSION["filter"] : "none";

function insertIntoDatabase($p)
{
    include "config/database.php";
    include "functions/initdb.php";
    try {
        $a = $db->prepare("SELECT id_user FROM users WHERE user_login= :user_login");
        $a->bindParam(':user_login', $_SESSION['login']);
        $a->execute();
        $tab = $a->fetch();

        $id= $tab['id_user'];

        $a = $db->prepare("INSERT INTO post (id_user, date_creation, id_post, nb_likes, nb_comments) VALUES (:id, NOW(), :cle, :nl, :nc)");
        $a->bindParam(':id', $id);
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
        echo "Impossible to insert inside table post. The mistake is: ".$e;
    }
}

function removeIntoDatabase($p)
{
    include "config/database.php";
    include "functions/initdb.php";
    try {    
        $sql = "DELETE FROM post WHERE id =  :nb";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nb', $p, PDO::PARAM_INT);
        $stmt->execute();
    }
    catch(PDOException $e) {
        echo "Impossible to delete post from table post! The mistake is : ".$e;
    }
}

function createmontage($photo_cam, $fil)
{
    $filt = imagecreatefrompng('img/'.$fil.'.png');
    $filter_w = imagesx($filt);
    $filter_h = imagesy($filt);

    $name = array('dolphin', 'lion', 'noel', 'asmshirt', 'asmlogo', 'cadre', 'cadre2', 'scarlett', 'collieraclous', 'kungfu', 'mrbean');
    $w = array('160', '140', '160', '350', '130', '400', '420', '200', '160', '170', '300');
    $h = array('160', '140', '160', '350', '130', '400', '420', '200', '160', '170', '200');
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
    $p = 'photos/'. $uiid . '.png';
    file_put_contents($p, $photo_cam);
    $sucess = imagepng($photo_cam, $p);
    insertIntoDatabase($p);
    echo $p;
}

function savephoto()
{
    $data = (isset($_POST["data"])) ? $_POST["data"] : NULL;
    $fil = (isset($_SESSION["filter"])) ? $_SESSION["filter"] : "none";


    if (!isset($data))
        $data = $_SESSION['import'];
    if ($data && $data !== '')
    {
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace(' ','+', $data);
        $data = base64_decode($data);
        $uiid = uniqid();
        if (!file_exists('photos')) {
            mkdir('photos');
        }
        $photo_cam = imagecreatefromstring($data);
        if ($fil !== 'none')
            createmontage($photo_cam, $fil);
            else
        {
            $p = 'photos/'. $uiid . '.png';
            insertIntoDatabase($p);
            file_put_contents($p, $data);
            echo $p;
        }
        die;
        }
    else
        echo "FAIL";
}

if (isset($fil) && $fil != "none")
    savephoto();
?>

