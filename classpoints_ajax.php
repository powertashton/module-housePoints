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

$classID = $_POST['classID'];

$data = array(
    'classID' => $classID,
    
);
$sql = "SELECT gibbonPerson.officialName, gibbonPerson.preferredName, SUM(hpPointStudent.points) AS total
    FROM gibbonPerson
    INNER JOIN gibbonStudentEnrolment
    ON gibbonStudentEnrolment.gibbonPersonID = gibbonPerson.gibbonPersonID
    LEFT JOIN hpPointStudent
    ON hpPointStudent.studentID = gibbonStudentEnrolment.gibbonPersonID
    AND hpPointStudent.yearID = gibbonStudentEnrolment.gibbonSchoolYearID
    

    WHERE gibbonStudentEnrolment.gibbonRollGroupID = :classID
    GROUP BY gibbonStudentEnrolment.gibbonPersonID
    ORDER BY gibbonPerson.officialName, gibbonPerson.preferredName";
/*
$sql = "SELECT gibbonPerson.officialName, gibbonPerson.preferredName, points.total
    FROM gibbonPerson
    INNER JOIN gibbonStudentEnrolment
    ON gibbonStudentEnrolment.gibbonPersonID = gibbonPerson.gibbonPersonID
    LEFT JOIN 
    (
        SELECT hpPointStudent.studentID, SUM(hpPointStudent.points) AS total
        FROM hpPointStudent
        WHERE hpPointStudent.yearID = :yearID
    ) AS points
    ON points.studentID = gibbonStudentEnrolment.gibbonPersonID

    WHERE gibbonStudentEnrolment.gibbonRollGroupID = :classID
    ORDER BY gibbonPerson.officialName, gibbonPerson.preferredName";
 * 
 */
//print $sql;
//print_r($data);
$rs = $dbh->prepare($sql);
$rs->execute($data);
$points = $rs->fetchAll();

$res = array(
    'points' => $points
);
echo json_encode($res);