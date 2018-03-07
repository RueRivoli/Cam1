<?php

try {
    $db = new PDO('mysql:host=localhost;dbname='.$db_name, 'root', $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
    echo "Impossible to connect to the db! The mistake is : ".$e;
}

?>