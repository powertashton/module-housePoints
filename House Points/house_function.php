<?php
use Gibbon\Forms\Form;

class pt {
    
    function __construct($guid, $connection2) {
        $this->dbh = $connection2;
        $this->guid = $guid;
        
        $this->yearID = $_SESSION[$guid]['gibbonSchoolYearID'];
        $this->teacherID = $_SESSION[$guid]['gibbonPersonID'];
        
        $this->houseList = readHouseList($this->dbh);
        $this->categoryList = readCategoryList($this->dbh, 'House');
    }

    function mainForm() {
        global $pdo;
        
        echo "<p>&nbsp;</p>";
        echo "<h3>Award house points to house</h3>";

        $form = Form::create('awardForm', '');
        $form->addHiddenValue('submit', 'submit');
        $form->addHiddenValue('teacherID', $this->teacherID);

        $sql = "SELECT gibbonHouse.gibbonHouseID AS value, gibbonHouse.name FROM gibbonHouse ORDER BY gibbonHouse.name";
        $row = $form->addRow();
            $row->addLabel('houseID', __('House'));
            $row->addSelect('houseID')->fromQuery($pdo, $sql)->isRequired()->placeholder();

        $highestAction = getHighestGroupedAction($this->guid, '/modules/House Points/house.php', $this->dbh);
        $unlimitedPoints = ($highestAction == 'Award house points_unlimited');

        $categories = array_reduce($this->categoryList->fetchAll(), function($group, $item) use ($unlimitedPoints) { 
            if (empty($item['categoryPresets']) && !$unlimitedPoints) return $group; 

            $group[$item['categoryID']] = $item['categoryName'];
            return $group;
        }, array());

        $row = $form->addRow();
            $row->addLabel('categoryID', __('Category'));
            $row->addSelect('categoryID')->fromArray($categories)->isRequired()->placeholder();

        $row = $form->addRow();
            $row->addLabel('points', __('Points'));
            $row->addTextField('points')->isDisabled()->placeholder(__('Select a category'));

        $row = $form->addRow();
            $row->addLabel('reason', __('Reason'));
            $row->addTextArea('reason')->setRows(2)->isRequired();
            
        $row = $form->addRow();
            $row->addButton('Submit', 'houseSave()', 'submit')->addClass('right');

        echo $form->getOutput();

        echo "<div>&nbsp;</div>";
        echo "<p id='msg' style='color:blue;'></p>";
        
        ?>
        <script>
            
            $('#awardForm #categoryID').change(function(){
                updateCategoryPoints();
            });

            $('#awardForm').change(function() {
                $('#msg').html('');
                $('#submit').prop('disabled', false).css({'background-color': 'black', 'color': 'white'});
            });
        </script>
        <?php
    }

}