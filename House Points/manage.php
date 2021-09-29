<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
use Gibbon\Forms\DatabaseFormFactory;
use Gibbon\Forms\Form;

require_once __DIR__ . '/moduleFunctions.php';

$page->breadcrumbs->add(__('View points individual'));
if (isActionAccessible($guid, $connection2,"/modules/House Points/individual.php")==FALSE) {
    //Acess denied
   $page->addError(__('You do not have access to this action.'));
} else {
    
    $form0 = Form::create('individualPoints', '');
    $form0->setFactory(DatabaseFormFactory::create($pdo));
    $form0->addHiddenValue('yearID', $session->get('gibbonSchoolYearID'));
    
    $form0->setTitle('Student Points');
    
    $row = $form0->addRow();
        $row->addLabel('studentID', __('Student'));
        $row->addSelectStudent('studentID', $session->get("gibbonSchoolYearID"), array())->placeholder();

    echo $form0->getOutput();
    
    echo "<br><div id='studentPoints'></div>";   
    
    $form1 = Form::create('pointsHouse', '');
    $form1->setFactory(DatabaseFormFactory::create($pdo));
    $form1->addHiddenValue('yearID', $session->get('gibbonSchoolYearID'));
    
    $form1->setTitle('House Points');
    $row = $form1->addRow();
        $row->addLabel('houseID', __('House'));
        $row->addSelectHouse('houseID')->placeholder();
    
    echo $form1->getOutput();
    
    echo "<br><div id='housePoints'></div>";


    //TODO: refine the JS and AJAX and maybe change the table into OO 
    //TODO: maybe move the deletes into a process page? also use a gateway
    ?>
    
    <script>
        $('#studentID').change(function() {
            managePointStudent($('#studentID').val());
        });
        function managePointStudent(studentID) {
            $.ajax({
                url: "./modules/House Points/individual_ajax.php",
                data: {
                    studentID: studentID
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var points = data.points;
            
                    var html = '';
                    if (points.length === 0) {
                        html = "<p>No points have been awarded to this student</p>";
                    } else {
                        var total = 0;
                        html += "<table style='width:100%;'>";
                            html += "<tr>";
                                html += "<th style='width:15%;'>Date</th>";
                                html += "<th style='width:10%;'>Points</th>";
                                html += "<th style='width:15%;'>Category</th>";
                                html += "<th style='width:30%;'>Reason</th>";
                                html += "<th style='width:20%;'>Awarded By</th>";
                                html += "<th style='width:10%;'>Action</th>";
                            html += "</tr>";
                            $.each(points, function(i, pt) {
                                total += parseInt(pt.points);
                                html += "<tr>";
                                    html += "<td>" + pt.awardedDate + "</td>";
                                    html += "<td>" + pt.points + "</td>";
                                    html += "<td>" + pt.categoryName + "</td>";
                                    html += "<td>" + pt.reason + "</td>";
                                    html += "<td>" + pt.teacherName + "</td>";
                                    html += "<td>";
                                        //html += "<a href='#' class='edit' data-id='" + pt.hpID + "'>Edit</a>&nbsp;&nbsp;"; 
                                        html += "<a href='#' class='deleteStudent' data-id='" + pt.hpID + "'>Delete</a>";
                                    html += "</td>";
                                html += "</tr>";
                            });
                    
                        html += "</table>";
                    }
            
                    $('#studentPoints').html(html);
            
            
                    $('.deleteStudent').click(function() {
                        if (confirm("Delete this entry?")) {
                            deletePointStudent(studentID, $(this).data('id'));
                        }
                    });
                }
            });
        }
        $('#houseID').change(function() {
                managePointHouse($('#houseID').val());
            });
    
        function managePointHouse(houseID) {
            $.ajax({
                url: "./modules/House Points/house_ajax.php",
                data: {
                    houseID: houseID
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var points = data.points;
            
                    var html = '';
                    if (points.length === 0) {
                        html = "<p>No points have been awarded to this house</p>";
                    } else {
                        var total = 0;
                        html += "<table style='width:100%;'>";
                            html += "<tr>";
                                html += "<th style='width:15%;'>Date</th>";
                                html += "<th style='width:10%;'>Points</th>";
                                html += "<th style='width:15%;'>Category</th>";
                                html += "<th style='width:30%;'>Reason</th>";
                                html += "<th style='width:20%;'>Awarded By</th>";
                                html += "<th style='width:10%;'>Action</th>";
                            html += "</tr>";
                            $.each(points, function(i, pt) {
                                total += parseInt(pt.points);
                                html += "<tr>";
                                    html += "<td>" + pt.awardedDate + "</td>";
                                    html += "<td>" + pt.points + "</td>";
                                    html += "<td>" + pt.categoryName + "</td>";
                                    html += "<td>" + pt.reason + "</td>";
                                    html += "<td>" + pt.teacherName + "</td>";
                                    html += "<td>";
                                        //html += "<a href='#' class='edit' data-id='" + pt.hpID + "'>Edit</a>&nbsp;&nbsp;"; 
                                        html += "<a href='#' class='deleteHouse' data-id='" + pt.hpID + "'>Delete</a>";
                                    html += "</td>";
                                html += "</tr>";
                            });
                    
                        html += "</table>";
                    }
            
                    $('#housePoints').html(html);
            
                    $('.deleteHouse').click(function() {
                        if (confirm("Delete this entry?")) {
                            deletePointHouse(houseID, $(this).data('id'));
                        }
                    });
                }
            });
        }
    
        function deletePointHouse(houseID, hpID) {
            $.ajax({
                url: "./modules/House Points/manage_ajax.php",
                data: {
                    hpID: hpID,
                    action: 'deleteItemHouse'
                },
                type: 'POST',
                //dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    managePointHouse(houseID);
                }
            });
        }

        function deletePointStudent(studentID, hpID) {
            $.ajax({
                url: "./modules/House Points/manage_ajax.php",
                data: {
                    hpID: hpID,
                    action: 'deleteItemStudent'
                },
                type: 'POST',
                //dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    managePointStudent(studentID);
                }
            });
        }
    </script>

    <?php
}

