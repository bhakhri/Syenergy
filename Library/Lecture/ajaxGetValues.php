<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FORM DURING EDIT
//
//
// Author : Rajeev Aggarwal
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LectureTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
  
if(trim($REQUEST_DATA['lectureTypeId'] ) != '') {
    require_once(MODEL_PATH . "/LectureManager.inc.php");
    $foundArray = LectureTypeManager::getInstance()->getLectureType(' WHERE lectureTypeId="'.$REQUEST_DATA['lectureTypeId'].'"');
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0; // no record found
    }
}

// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:15p
//Updated in $/LeapCC/Library/Lecture
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Lecture
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:59p
//Updated in $/Leap/Source/Library/Lecture
//updated formatting
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 6/30/08    Time: 3:39p
//Updated in $/Leap/Source/Library/Lecture
//upodated Ajax code
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/25/08    Time: 4:34p
//Updated in $/Leap/Source/Library/Lecture
//updated the defects and comments
?>

