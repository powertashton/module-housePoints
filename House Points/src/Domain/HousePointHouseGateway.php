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


    
}
