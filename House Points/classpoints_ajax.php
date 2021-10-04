<?php
include  '../../gibbon.php';

$dbh = $connection2;

$classID = $_POST['classID'];
$yearID = $_POST['yearID'];

$data = array(
    'classID' => $classID,
    'yearID' => $yearID
);
$sql = "SELECT gibbonPerson.officialName, gibbonPerson.surname, gibbonPerson.preferredName, gibbonHouse.name as houseName, SUM(hpPointStudent.points) AS total
    FROM gibbonPerson
    INNER JOIN gibbonStudentEnrolment
    ON gibbonStudentEnrolment.gibbonPersonID = gibbonPerson.gibbonPersonID
    LEFT JOIN hpPointStudent
    ON hpPointStudent.studentID = gibbonStudentEnrolment.gibbonPersonID
    AND hpPointStudent.yearID = gibbonStudentEnrolment.gibbonSchoolYearID
    JOIN gibbonHouse ON (gibbonHouse.gibbonHouseID=gibbonPerson.gibbonHouseID)
    WHERE gibbonStudentEnrolment.gibbonSchoolYearID= :yearID
    AND gibbonStudentEnrolment.gibbonFormGroupID = :classID
    GROUP BY gibbonStudentEnrolment.gibbonPersonID
    ORDER BY gibbonPerson.surname, gibbonPerson.preferredName";

$rs = $dbh->prepare($sql);
$rs->execute($data);
$points = $rs->fetchAll();

$res = array(
    'points' => $points
);
echo json_encode($res);
