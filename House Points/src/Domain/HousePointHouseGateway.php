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
class HousePointHouseGateway extends QueryableGateway
{
    use TableAware;

    private static $tableName = 'hpPointHouse';
    private static $primaryKey = 'hpID';
    private static $searchableColumns = [];

    
    public function selectAllPoints($yearID) {
        $select = $this
            ->newSelect()
            ->from('gibbonHouse')
            ->cols(['gibbonHouse.gibbonHouseID AS houseID', 'gibbonHouse.name AS houseName', 'gibbonHouse.logo as houseLogo','COALESCE(pointStudent.total + pointHouse.total, pointStudent.total, pointHouse.total, 0) AS total'])
            ->joinSubSelect(
                'left',
                'SELECT gibbonPerson.gibbonHouseID AS houseID,
                    SUM(hpPointStudent.points) AS total
                    FROM hpPointStudent
                    INNER JOIN gibbonPerson
                    ON hpPointStudent.studentID = gibbonPerson.gibbonPersonID
                    WHERE hpPointStudent.yearID=:yearID
                    GROUP BY gibbonPerson.gibbonHouseID ',
                'pointStudent',
                'pointStudent.houseID = gibbonHouse.gibbonHouseID'
            )->joinSubSelect(
                'left',
                'SELECT hpPointHouse.houseID,
                        SUM(hpPointHouse.points) AS total
                        FROM hpPointHouse
                        WHERE hpPointHouse.yearID=:yearID
                        GROUP BY hpPointHouse.houseID',
                'pointHouse', 
                'pointHouse.houseID = gibbonHouse.gibbonHouseID'
            )
            ->bindValue('yearID', $yearID)
            ->orderBy(['total']);

        return $this->runSelect($select);
    }
    
}
