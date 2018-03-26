<?php

include 'database.php';

try {
    $db = new PDO($db_dsn, $db_user, $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
}
catch(PDOException $e) {
    echo "Impossible to log on the servor! The mistake is : ".$e;
}


try{
    $req = "CREATE DATABASE IF NOT EXISTS `".$db_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    $db->query($req);
    $db->query("USE ".$db_name);
}
catch(PDOException $e) {
    echo "Can't manage to create the database! The mistake is : ".$e;
}

include 'functions/initdb.php';

try{
    if($db){
        /*table users*/
	    $req = "CREATE TABLE IF NOT EXISTS users(
                    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    login VARCHAR(15),
                    email VARCHAR(25),
                    pswd VARCHAR(150),
                    cle VARCHAR(300) DEFAULT NULL,
                    activity TINYINT DEFAULT 0,
                    notif TINYINT DEFAULT 0,
                    PRIMARY KEY(id)
				    );";
        $coord = $db->prepare($req);
        $coord->execute();
        
        /*table post*/

        $req = "CREATE TABLE IF NOT EXISTS post(
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            login VARCHAR(15),
            date_creation DATETIME,
            post_id VARCHAR(300) DEFAULT NULL,
            nb_likes INT DEFAULT 0,
            nb_comments INT DEFAULT 0,
            PRIMARY KEY(id)
            );";
        $requ = $db->prepare($req);
        $requ->execute();


        $req = "CREATE TABLE IF NOT EXISTS comments(
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id VARCHAR(300) DEFAULT NULL,
            login VARCHAR(15) DEFAULT NULL,
            text VARCHAR(500) DEFAULT NULL,
            date_creation DATETIME NOT NULL,
            PRIMARY KEY(id)
            );";
        $requ = $db->prepare($req);
        $requ->execute();


        $req = "CREATE TABLE IF NOT EXISTS likes(
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id VARCHAR(300) DEFAULT NULL,
            login VARCHAR(15) DEFAULT NULL,
            PRIMARY KEY(id)
            );";
        $requ = $db->prepare($req);
        $requ->execute();

        $sql = $db->prepare('SELECT * FROM users');
        $sql->execute();
        
        $nb = $sql->rowCount();
        }
    }
    catch(PDOException $e) {
        echo "Une erreur est intervenue! L'erreur est : ".$e;
}

if ($nb === 0)
{
    try {
        $date = date('Y-m-d H:i:s');
        $a = $db->prepare("INSERT INTO users (login, email, pswd, cle, activity, notif) VALUES (?, ?, ?, ?, ?, ?)");
        $a->execute(array("Louis14", "flgallois@gmail.com", hash("whirlpool", "Louis14"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Louis16", "flgallois@gmail.com", hash("whirlpool", "Louis16"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Henri4", "flgallois@gmail.com", hash("whirlpool", "Henri4"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Jmarsal", "flgallois@gmail.com", hash("whirlpool", "Jmarsal11"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("George6", "flgallois@gmail.com", hash("whirlpool", "George6"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("CR7", "flgallois@gmail.com", hash("whirlpool", "CR7"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Gattuso", "flgallois@gmail.com", hash("whirlpool", "Gattuso6"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Falcao", "flgallois@gmail.com", hash("whirlpool", "Falcao9"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("HowardCarter", "flgallois@gmail.com", hash("whirlpool", "HowardCarter14"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Nessie", "flgallois@gmail.com", hash("whirlpool", "Nessie2"), hash("whirlpool", rand()), "1", "1"));
        $a->execute(array("Churchill", "flgallois@gmail.com", hash("whirlpool", "Churchill5"), hash("whirlpool", rand()), "1", "1"));

        $a = $db->prepare("INSERT INTO post (login, date_creation, post_id, nb_likes, nb_comments) VALUES (?, ?, ?, ?, ?)");
        $a->execute(array("Louis14", $date, "ressources/prepared/galglaces.jpg", "4", "0"));
        $a->execute(array("Churchill", $date, "ressources/prepared/churchill.jpg", "2", "0"));
        $a->execute(array("Henri4",$date, "ressources/prepared/pau.jpg", "4", "1"));
        $a->execute(array("MarieAntoinette", $date, "ressources/prepared/mariea.jpg", "12", "0"));
        $a->execute(array("CR7", $date, "ressources/prepared/cr7.jpg", "6", "2"));
        $a->execute(array("Gattuso", $date, "ressources/prepared/gatt.jpg", "2", "0"));
        $a->execute(array("Churchill", $date, "ressources/prepared/church2.jpg", "2", "0"));
        $a->execute(array("Jmarsal", $date, "ressources/prepared/beach.png", "6", "0"));
        $a->execute(array("HowardCarter", $date, "ressources/prepared/egypte.jpeg", "4", "0"));
        $a->execute(array("Louis16",$date, "ressources/prepared/louis16.jpg", "12", "2"));
        $a->execute(array("Falcao", $date, "ressources/prepared/falcao1.jpg", "12", "1"));
        $a->execute(array("Jmarsal", $date, "ressources/prepared/jm.jpg", "6", "0"));
        $a->execute(array("Nessie", $date, "ressources/prepared/lochness.jpg", "6", "0"));
        $a->execute(array("HowardCarter", $date, "ressources/prepared/carter3.jpg", "2", "0"));
        $a->execute(array("Louis16", $date, "ressources/prepared/louis16b.jpg", "0", "5"));
        $a->execute(array("Louis14",$date, "ressources/prepared/chavers.jpg", "1", "0"));
        $a->execute(array("HowardCarter", $date, "ressources/prepared/carter2.jpg", "1", "0"));
        $a->execute(array("George6", $date, "ressources/prepared/bigben.jpeg", "6", "0"));
        $a->execute(array("Nessie", $date, "ressources/prepared/urquart.jpg", "2", "0"));

        $a = $db->prepare("INSERT INTO comments (post_id, login, text, date_creation) VALUES (?, ?, ?, ?)");
        $a->execute(array("ressources/prepared/falcao1.jpg", "CR7", "Futur ballon d'or", "2017-03-24 17:45:12"));
        $a->execute(array("ressources/prepared/cr7.jpg", "Gattuso", "T es nul", "2016-03-24 12:55:02"));
        $a->execute(array("ressources/prepared/louis16.jpg", "MarieAntoinette", "Toi tu vas pas faire long feu !", "2016-03-15 05:55:02"));
        $a->execute(array("ressources/prepared/pau.jpg", "Churchill", "Je suis pas la manque de pau ;D", "2015-05-29 13:55:02"));
        $a->execute(array("ressources/prepared/cr7.jpg", "CR7", "Beau goss", "2018-01-01 01:05:32"));
        $a->execute(array("ressources/prepared/louis16.jpg", "MarieAntoinette", "Toi tu vas pas faire long feu !", "2016-03-15 05:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "Jmarsal", "Je suis pas la manque de pau ;D", "2015-05-29 13:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "Nessie", "Y en a marre ou est le painnnn???", "1989-07-01 01:05:32"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "Falcao", "Vous nous prenez pour des imbeciles?", "2016-03-15 05:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "Marieantoinette", "Du calme", "2015-05-29 13:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "CR7", "ouaaii", "2018-01-01 01:05:32"));
        }
    catch(PDOException $e) {
        echo "Impossible to insert the instances! The mistake is: ".$e;
}

}

?>