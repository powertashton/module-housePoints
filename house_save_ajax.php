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

$formData = array();
parse_str($_POST['formData'], $formData);

$teacherID = $formData['teacherID'];
$houseID = $formData['houseID'];
$categoryID = $formData['categoryID'];
$points = $formData['points'];
$reason = $formData['reason'];
$yearID = $_SESSION[$guid]['gibbonSchoolYearID'];

$msg = '';
if ($houseID == 0) {
    $msg .= "Please select a house<br />"; 
}
if ($categoryID == 0) {
    $msg .= "Please select a category<br />"; 
}
if ($points<1 || $points>10) {
    $msg .= "Please award between 1 and 10 points<br />"; 
}

if ($msg == '') {
    $data = array(
        'houseID' => $houseID,
        'categoryID' => $categoryID,
        'points' => $points,
        'reason' => $reason,
        'yearID' => $yearID,
        'awardedDate' => date('Y-m-d'),
        'awardedBy' => $teacherID
    );
    $sql = "INSERT INTO hpPointHouse
        SET houseID = :houseID,
        categoryID = :categoryID,
        points = :points,
        reason = :reason,
        yearID = :yearID,
        awardedDate = :awardedDate,
        awardedBy = :awardedBy";
    $rs = $dbh->prepare($sql);
    $ok = $rs->execute($data);
    if ($ok) {
        $msg = "Points successfully added";
    } else {
        $msg = "Problem - contact system adminstrator";
    }
}

echo $msg;