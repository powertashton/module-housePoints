<?php
class pt {
    
    function pt($guid, $connection2) {
        $this->dbh = $connection2;
        $this->guid = $guid;
        
        $this->yearID = $_SESSION[$guid]['gibbonSchoolYearID'];
        $this->teacherID = $_SESSION[$guid]['gibbonPersonID'];
        
        $this->houseList = readHouseList($this->dbh);
        $this->categoryList = readCategoryList($this->dbh);
    }

    function mainForm() {
        
        echo "<p>&nbsp;</p>";
        echo "<h3>Award house points to house</h3>";
        echo "<form name='awardForm' id='awardForm' method='post' action=''>";
            echo "<input type='hidden' name='teacherID' id='teacherID' value='".$this->teacherID."' />";
            echo "<table style='width:100%'>";
                // select student
                echo "<tr>";
                    echo "<th style='width:20%;'><label for='houseID'>House</label></th>";
                    echo "<td style='width:80%;'>";
                        selectHouse($this->houseList);
                    echo "</td>";
                echo "</tr>";
            
            
                // select category
                echo "<tr>";
                    echo "<th><label for='categoryID'>Category</label></th>";
                    echo "<td>";
                        echo "<select name='categoryID' style='float:left;'>";
                            echo "<option value='0'>Please select</option>";
                            while ($row = $this->categoryList->fetch()) {
                                echo "<option value='".$row['categoryID']."'>".$row['categoryName']."</option>";
                            }
                        echo "</select>";
                    echo "</td>";
                echo "</tr>";
                
                // points
                echo "<tr>";
                    echo "<th><label for='points'>Points</label></th>";
                    echo "<td>";
                        echo "<input type='number' name='points' value='1' style='font-size:12pt;text-align:center;padding:1pt' max='10' min='1' required/>";
                    echo "</td>";
                echo "</tr>";
            
                // reason
                echo "<tr>";
                    echo "<th><label for='reason'>Reason</label></th>";
                    echo "<td>";
                        echo "<textarea name='reason' rows='4' cols'60' style='width:100%;'></textarea>";
                    echo "</td>";
                echo "</tr>";
                
            echo "</table>";
            // save
            echo "<div><input type='button' name='submit' id='submit' value='Submit' onclick='houseSave()' disabled style='background-color:#cccccc;color:gray' /></div>";
        echo "</form>";
        echo "<div>&nbsp;</div>";
        echo "<p id='msg' style='color:blue;'></p>";
        
        ?>
        <script>
            $('#awardForm').change(function() {
                $('#msg').html('');
                $('#submit').prop('disabled', false).css({'background-color': 'black', 'color': 'white'});
            });
        </script>
        <?php

    }

}