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

$page->breadcrumbs->add(__('View points class'));

if (isActionAccessible($guid, $connection2,"/modules/House Points/classpoints.php")==FALSE) {
    //Acess denied
    $page->addError(__('You do not have access to this action.'));
} else {
    
    $form = Form::create('classPoints', '');
    $form->setFactory(DatabaseFormFactory::create($pdo));
    $form->addHiddenValue('yearID', $session->get('gibbonSchoolYearID'));
    
    $row = $form->addRow();
        $row->addLabel('classID', __('Class'));
        $row->addSelectFormGroup('classID', $session->get('gibbonSchoolYearID'))->placeholder();
    
    echo $form->getOutput();
    
    echo "<br><div id='studentPoints'></div>";


    //TODO: refine the JS and AJAX and maybe change the table into OO
    ?>
    <script>
        $('#classID').change(function() {
            showClassPoint($('#classID').val(), $('input:hidden[name=yearID]').val());
        });
        function showClassPoint(classID, yearID) {    
            $.ajax({
                url: "./modules/House Points/classpoints_ajax.php",
                data: {
                    classID: classID,
                    yearID: yearID
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
            
                    $('#studentPoints').html(html);
                }
            });
        }
    </script>
    <?php


}
