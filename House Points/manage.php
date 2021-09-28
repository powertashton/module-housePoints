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
    
    //TODO: refine the JS and AJAX and maybe change the table into OO 
    //TODO: ADD DELETE
    ?>
        <script>
        $('#studentID').change(function() {
            showIndividualPoint($('#studentID').val());
        });
        function showIndividualPoint(studentID) {
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
                            html += "</tr>";
                            $.each(points, function(i, pt) {
                                total += parseInt(pt.points);
                                html += "<tr>";
                                    html += "<td>" + pt.awardedDate + "</td>";
                                    html += "<td>" + pt.points + "</td>";
                                    html += "<td>" + pt.categoryName + "</td>";
                                    html += "<td>" + pt.reason + "</td>";
                                    html += "<td>" + pt.teacherName + "</td>";
                                html += "</tr>";
                            });
                            html += "<tr>";
                                html += "<td><strong>Total</strong></td>";
                                html += "<td colspan='4'><strong>" + total + " points</strong></td>";
                            html += "</tr>";
                        html += "</table>";
                    }
            
                    $('#studentPoints').html(html);
                }
            });
        }
        </script>
    <?php
    $form1 = Form::create('pointsClass', '');
    $form1->setFactory(DatabaseFormFactory::create($pdo));
    $form1->addHiddenValue('yearID', $session->get('gibbonSchoolYearID'));
    
    $form1->setTitle('Class Points');
    $row = $form1->addRow();
        $row->addLabel('classID', __('Class'));
        $row->addSelectFormGroup('classID', $session->get('gibbonSchoolYearID'))->placeholder();
    
    echo $form1->getOutput();
    
    echo "<br><div id='classPoints'></div>";


    //TODO: refine the JS and AJAX and maybe change the table into OO
     //TODO: ADD DELETE
    ?>
    <script>
        $('#classID').change(function() {
            showClassPoint($('#classID').val());
        });
        function showClassPoint(classID) {    
            $.ajax({
                url: "./modules/House Points/classpoints_ajax.php",
                data: {
                    classID: classID
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var points = data.points;
                    var total;
            
                    var html = '';
                    html += "<table style='width:100%;'>";
                        html += "<tr>";
                            html += "<th style='width:40%;'>Name</th>";
                            html += "<th style='width:25%;'>Points</th>";
                            html += "<th style='width:15%;'>House</th>";
                        html += "</tr>";
                        $.each(points, function(i, pt) {
                            total = pt.total === null ? 0 : pt.total;
                            html += "<tr>";
                                html += "<td>" + pt.surname + ", " + pt.preferredName + "</td>";
                                html += "<td>" + total + "</td>";
                                html += "<td>" + pt.houseName + "</td>";
                            html += "</tr>";
                        });
                    html += "</table>";
            
                    $('#classPoints').html(html);
                }
            });
        }
    </script>
    <?php
}

