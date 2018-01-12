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

$houseID = $_POST['houseID'];

$data = array(
    'houseID' => $houseID,
    'yearID' => $_SESSION[$guid]['gibbonSchoolYearID']
);
$sql = "SELECT hpPointHouse.hpID, 
    DATE_FORMAT(hpPointHouse.awardedDate, '%d/%m/%Y') AS awardedDate,
    hpPointHouse.points, 
    hpCategory.categoryName,
    hpPointHouse.reason, 
    CONCAT(gibbonPerson.title, ' ', gibbonPerson.preferredName, ' ', gibbonPerson.surname) AS teacherName
    FROM hpPointHouse
    INNER JOIN hpCategory
    ON hpCategory.categoryID = hpPointHouse.categoryID
    INNER JOIN gibbonPerson
    ON gibbonPerson.gibbonPersonID = hpPointHouse.awardedBy
    WHERE hpPointHouse.houseID = :houseID
    AND hpPointHouse.yearID = :yearID
    ORDER BY awardedDate DESC";
$rs = $dbh->prepare($sql);
$rs->execute($data);
$points = $rs->fetchAll();

$res = array(
    'points' => $points
);
echo json_encode($res);