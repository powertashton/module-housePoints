<?php
include  "../../config.php";
include "../../functions.php";

//New PDO DB connection
try {
    $connection2=new PDO("mysql:host=$databaseServer;
            dbname=$databaseName;
            charset=utf8", $databaseUsername, $databasePassword);
    $connection2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // reset coding
}
catch(PDOException $e) {
    echo $e->getMessage();
}

$dbh = $connection2;
$action = $_POST['action'];

switch ($action) {
    case 'deleteItemStudent':
        $hpID = $_POST['hpID'];

        $data = array(
            'hpID' => $hpID
        );
        $sql = "DELETE FROM hpPointStudent
            WHERE hpPointStudent.hpID = :hpID";
        $rs = $dbh->prepare($sql);
        echo $rs->execute($data);
        break;
    
    case 'deleteItemHouse':
        $hpID = $_POST['hpID'];

        $data = array(
            'hpID' => $hpID
        );
        $sql = "DELETE FROM hpPointHouse
            WHERE hpPointHouse.hpID = :hpID";
        $rs = $dbh->prepare($sql);
        echo $rs->execute($data);
        break;
}
