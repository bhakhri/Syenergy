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
    
if(trim($REQUEST_DATA['rollNo'] ) != '') {
    require_once(MODEL_PATH . "/DisciplineManager.inc.php");
    $foundArray = DisciplineManager::getInstance()->getStudentDetail("  AND st.rollNo='".trim(add_slashes($REQUEST_DATA['rollNo']))."'");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetStudentValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Library/Discipline
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
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