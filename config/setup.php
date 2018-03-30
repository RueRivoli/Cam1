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
                    id_user SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    user_login VARCHAR(15),
                    email VARCHAR(25),
                    pswd VARCHAR(150),
                    cle VARCHAR(300) DEFAULT NULL,
                    activity TINYINT DEFAULT 0,
                    notif TINYINT DEFAULT 0,
                    PRIMARY KEY(id_user)
				    );";
        $coord = $db->prepare($req);
        $coord->execute();
        
        /*table post*/

        $req = "CREATE TABLE IF NOT EXISTS post(
            id_post SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            id_user SMALLINT UNSIGNED NOT NULL,
            date_creation DATETIME,
            post_url VARCHAR(300) DEFAULT NULL,
            nb_likes INT DEFAULT 0,
            nb_comments INT DEFAULT 0,
            PRIMARY KEY(id_post)
            );";
        $req = $db->prepare($req);
        $req->execute();


        $req = "CREATE TABLE IF NOT EXISTS comments(
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id VARCHAR(300) DEFAULT NULL,
            id_user SMALLINT UNSIGNED NOT NULL,
            text VARCHAR(500) DEFAULT NULL,
            date_creation DATETIME NOT NULL,
            PRIMARY KEY(id)
            );";
        $req = $db->prepare($req);
        $req->execute();


        $req = "CREATE TABLE IF NOT EXISTS likes(
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id VARCHAR(300) DEFAULT NULL,
            id_user SMALLINT UNSIGNED NOT NULL,
            PRIMARY KEY(id)
            );";
        $req = $db->prepare($req);
        $req->execute();

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
        $a = $db->prepare("INSERT INTO users (user_login, email, pswd, cle, activity, notif) VALUES (?, ?, ?, ?, ?, ?)");
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


        $a = $db->prepare("INSERT INTO post (id_user, date_creation, post_url, nb_likes, nb_comments) VALUES (?, ?, ?, ?, ?)");
        $a->execute(array("10", $date, "ressources/prepared/galglaces.jpg", "4", "0"));
        $a->execute(array("4", $date, "ressources/prepared/churchill.jpg", "2", "0"));
        $a->execute(array("6",$date, "ressources/prepared/pau.jpg", "4", "1"));
        $a->execute(array("2", $date, "ressources/prepared/mariea.jpg", "12", "0"));
        $a->execute(array("7", $date, "ressources/prepared/cr7.jpg", "6", "2"));
        $a->execute(array("1", $date, "ressources/prepared/gatt.jpg", "2", "0"));
        $a->execute(array("4", $date, "ressources/prepared/church2.jpg", "2", "0"));
        $a->execute(array("9", $date, "ressources/prepared/beach.png", "6", "0"));
        $a->execute(array("5", $date, "ressources/prepared/egypte.jpeg", "4", "0"));
        $a->execute(array("5",  $date, "ressources/prepared/louis16.jpg", "12", "2"));
        $a->execute(array("3", $date, "ressources/prepared/falcao1.jpg", "12", "1"));
        $a->execute(array("3", $date, "ressources/prepared/jm.jpg", "6", "0"));
        $a->execute(array("2", $date, "ressources/prepared/lochness.jpg", "6", "0"));
        $a->execute(array("9", $date, "ressources/prepared/carter3.jpg", "2", "0"));
        $a->execute(array("11", $date, "ressources/prepared/louis16b.jpg", "0", "5"));
        $a->execute(array("1",$date, "ressources/prepared/chavers.jpg", "1", "0"));
        $a->execute(array("1", $date, "ressources/prepared/carter2.jpg", "1", "0"));
        $a->execute(array("8", $date, "ressources/prepared/bigben.jpeg", "6", "0"));
        $a->execute(array("10", $date, "ressources/prepared/urquart.jpg", "2", "0"));

        $a = $db->prepare("INSERT INTO comments (post_id, id_user, text, date_creation) VALUES (?, ?, ?, ?)");
        $a->execute(array("ressources/prepared/falcao1.jpg", "4", "Futur ballon d'or", "2017-03-24 17:45:12"));
        $a->execute(array("ressources/prepared/cr7.jpg", "8", "T es nul", "2016-03-24 12:55:02"));
        $a->execute(array("ressources/prepared/louis16.jpg", "10", "Toi tu vas pas faire long feu !", "2016-03-15 05:55:02"));
        $a->execute(array("ressources/prepared/pau.jpg", "9", "Je suis pas la manque de pau ;D", "2015-05-29 13:55:02"));
        $a->execute(array("ressources/prepared/cr7.jpg", "4", "Beau goss", "2018-01-01 01:05:32"));
        $a->execute(array("ressources/prepared/louis16.jpg", "10", "Toi tu vas pas faire long feu !", "2016-03-15 05:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "2", "Je suis pas la manque de pau ;D", "2015-05-29 13:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "6", "Y en a marre ou est le painnnn???", "1989-07-01 01:05:32"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "5", "Vous nous prenez pour des imbeciles?", "2016-03-15 05:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "10", "Du calme", "2015-05-29 13:55:02"));
        $a->execute(array("ressources/prepared/louis16b.jpg", "4", "ouaaii", "2018-01-01 01:05:32"));
        }
    catch(PDOException $e) {
        echo "Impossible to insert the instances! The mistake is: ".$e;
}

}

?>