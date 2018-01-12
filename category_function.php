<?php
class cat {
    
    function cat($guid, $connection2) {
        $this->dbh = $connection2;
        $this->guid = $guid;
        
        // check if add, edit or delete is required
        $this->mode = getMode();
        
        $this->categoryID = $this->getCategoryID();
        
        if ($this->mode == 'delete') {
            $ok = $this->categoryDelete($connection2);
            if ($ok) {
                $this->categoryID = 0;
                $this->mode = '';
            }
        }
        
        if (isset($_POST['cancel'])) {
            $this->categoryID = 0;
            $this->mode = '';
        }
        
        if (isset($_POST['save'])) {
            $this->categorySave();
            $this->mode = '';
        }
        $this->categoryList = readCategoryList($this->dbh);
        
    }
    
    
    function formDefine() {
        echo "<td style='width:70%'>";
            echo "<input type='hidden' name='categoryID' value='".$this->categoryID."' />";
            echo "<input type='text' name='categoryName' value='".$this->categoryName."' style='width:100%' />";
        echo "</td>";
        echo "<td style=' style='width:30%';text-align:center'>";
            echo "<input type='submit' name='save' value='Save' />";
            echo "<input type='submit' name='cancel' value='Cancel' />";
        echo "</td>";
    }
    
    function mainform() {
        $linkPath = $_SESSION[$this->guid]['absoluteURL'].'/index.php?q=/modules/'.$_SESSION[$this->guid]["module"].'/category.php';
        $linkNew = $linkPath."&amp;mode=new";
        $deleteIcon = $this->modpath."/images/delete.png";
        $editIcon = $this->modpath."/images/edit.png";
        
        echo "<p>&nbsp;</p>";
        echo "<p><a href='$linkNew'>Add new</a></p>";
        echo "<form name='catform' method='post' action='' />";
            echo "<table style='width:100%;'>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th>Category</th>";
                        echo "<th>Action</th>";
                    echo "</tr>";
                echo "</thead>";

                echo "<tbody>";
                    if ($this->categoryList->rowCount() == 0 || $this->mode == 'new') {
                        $this->categoryID = 0;
                        $this->categoryName = '';
                        $this->formDefine();
                    }
                    while ($row = $this->categoryList->fetch()) {
                        $linkEdit = $linkPath.
                            "&amp;categoryID=".$row['categoryID'].
                            "&amp;mode=edit";
                        $messageDelete = "WARNING All points associated with this category will be lost.  Delete ".$row['categoryName']."?";
                        $linkDelete = "window.location = \"$linkPath&amp;categoryID=".$row['categoryID'].
                                "&amp;mode=delete\"";
                        echo "<tr>";   
                            if (($this->mode == 'edit' && $row['categoryID'] == $this->categoryID)) {
                                $this->categoryID = $row['categoryID'];
                                $this->categoryName = $row['categoryName'];
                                $this->formDefine();
                            } else {
                                echo "<td style='width:70%'>".$row['categoryName']."</td>";
                                echo "<td style='width:30%;'>";
                                    echo "<a href='$linkEdit'>Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='if (confirm(\"$messageDelete\")) $linkDelete'>Delete</a>";
                                echo "</td>";

                            }
                        echo "</tr>";
                    }
                echo "</tbody>";
            echo "</table>";
        echo "</form>";
    }
    
    function categoryDelete() {
        $data = array(
            'categoryID' => $this->categoryID
        );
        $sql = "DELETE FROM hpCategory
            WHERE categoryID = :categoryID";
        $rs = $this->dbh->prepare($sql);
        $rs->execute($data);
    }
    
    function categorySave() {
        $categoryID = $_POST['categoryID'];
        $categoryName = trim($_POST['categoryName']);
        
        if ($categoryName === '') {
            return;
        }
        // check if category exists
        $data = array(
            'categoryName' => $categoryName
        );        
        $sql = "SELECT categoryID
            FROM hpCategory
            WHERE categoryName = :categoryName";
        $rs = $this->dbh->prepare($sql);
        $rs->execute($data);
        $ok = true;
        if ($rs->rowCount() > 0) {
            $row = $rs->fetch();
            if ($row['categoryID'] != $categoryID) {
                $ok = false;
            }
        }
        
        if ($ok) {
            if ($categoryID > 0) {
                $data = array(
                    'categoryID' => $categoryID,
                    'categoryName' => $categoryName
                );
                $sql = "UPDATE hpCategory
                    SET categoryName = :categoryName
                    WHERE categoryID = :categoryID";
            } else {
                $data = array(
                    'categoryName' => $categoryName
                );
                $sql = "INSERT INTO hpCategory
                    SET categoryName = :categoryName";
            }
            $rs = $this->dbh->prepare($sql);
            $rs->execute($data);
        }
    }
    
    
    
    ////////////////////////////////////////////////////////////////////////////////
    function getCategoryID() {
        // check if parameter has been passed to current page
        $categoryID = '';
        if (isset($_POST['categoryID'])) {
            $categoryID = $_POST['categoryID'];
        } else {
            if (isset($_GET['categoryID'])) {
                $categoryID = $_GET['categoryID'];
            }
        }
        return $categoryID;
    }
    ////////////////////////////////////////////////////////////////////////////////

}