<?php
namespace Gibbon\Module\HousePoints\Domain;

use Gibbon\Domain\Traits\TableAware;
use Gibbon\Domain\QueryCriteria;
use Gibbon\Domain\QueryableGateway;

/**
 * Technician Gateway
 *
 * @version v20
 * @since   v20
 */
class HousePointStudentGateway extends QueryableGateway
{
    use TableAware;

    private static $tableName = 'hpPointStudent';
    private static $primaryKey = 'hpID';
    private static $searchableColumns = [];
    
    public function selectStudentPoints($studentID, $yearID) {
        $select = $this
            ->newSelect()
            ->from('hpPointStudent')
            ->cols(['hpPointStudent.hpID','DATE_FORMAT(hpPointStudent.awardedDate, \'%d/%m/%Y\') AS awardedDate','hpPointStudent.points','hpCategory.categoryName','hpPointStudent.reason','CONCAT(gibbonPerson.title, \' \', gibbonPerson.preferredName, \' \', gibbonPerson.surname) AS teacherName'])
            ->innerJoin('hpCategory', 'hpCategory.categoryID=hpPointStudent.categoryID')
            ->innerJoin('gibbonPerson', 'gibbonPerson.gibbonPersonID=hpPointStudent.awardedBy')
            ->where('hpPointStudent.studentID = :studentID')
            ->bindValue('studentID', $studentID)
            ->where('hpPointStudent.yearID = :yearID')
            ->bindValue('yearID', $yearID)
            ->orderBy(['hpPointStudent.awardedDate']);

        return $this->runSelect($select);
    }


    public function selectStudentPointsSum($studentID, $yearID) {
        $select = $this
            ->newSelect()
            ->from('hpPointStudent')
            ->cols(['SUM(points) as points'])
            ->where('hpPointStudent.studentID = :studentID')
            ->bindValue('studentID', $studentID)
            ->where('hpPointStudent.yearID = :yearID')
            ->bindValue('yearID', $yearID)
            ->orderBy(['hpPointStudent.awardedDate']);

        return $this->runSelect($select);
    }

}
