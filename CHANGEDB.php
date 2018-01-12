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



