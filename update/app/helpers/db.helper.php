<?php



function setupDatabase($host,$user,$pass,$db,$sql){

    try {
        $dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $dbh->exec($sql);
        return TRUE;
    } catch (PDOException $e) {
        return FALSE;
    }

}






