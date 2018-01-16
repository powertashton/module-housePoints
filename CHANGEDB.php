<?php
$sql=array();
$count=0;
$sql[$count][0]="1.00" ; // version number
$sql[$count][1]="" ; // sql statements
$count++;

$sql[$count][0]="1.01" ; // version number
$sql[$count][1]="" ; // sql statements
$count++;

$sql[$count][0]="1.02" ; // version number
$sql[$count][1]="
ALTER TABLE hpCategory ADD categoryType ENUM('House','Student') NOT NULL DEFAULT 'House' AFTER categoryOrder;end
ALTER TABLE hpCategory ADD categoryPresets TEXT NOT NULL AFTER categoryType;end
" ;
$count++;

$sql[$count][0]="1.03" ; // version number
$sql[$count][1]="
UPDATE gibbonAction SET category='Manage' WHERE (name='Manage points' OR name='Categories') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
UPDATE gibbonAction SET category='Award' WHERE (name='Award student points' OR name='Award house points') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
UPDATE gibbonAction SET category='View' WHERE (name='View points overall' OR name='View points individual' OR name='View points class' OR name='View my points') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
" ;
$count++;


