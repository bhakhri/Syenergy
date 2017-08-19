<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisciplineMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$i=0;    
if(trim($REQUEST_DATA['disciplineId'] ) != '') {
    require_once(MODEL_PATH . "/DisciplineManager.inc.php");
    $foundArray = DisciplineManager::getInstance()->getDiscipline(' AND disciplineId="'.$REQUEST_DATA['disciplineId'].'"');
	$foundArray[$i]['remarks'] = html_entity_decode($foundArray[$i]['remarks']);
	$foundArray[$i]['reportedBy'] = html_entity_decode($foundArray[$i]['reportedBy']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:04
//Created in $/LeapCC/Library/Discipline
//Created 'Discipline' Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Library/Discipline
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Library/Discipline
//Created module 'Discipline'
?>