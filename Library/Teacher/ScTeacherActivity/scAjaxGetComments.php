<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE comments div
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');

UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['commentId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $foundArray = ScTeacherManager::getInstance()->getComments($REQUEST_DATA['commentId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
         //echo json_encode($foundArray[0]);
        echo ' 
         {"commentId":"'.$foundArray[0]['commentId'].'",
         "subject":"'.$foundArray[0]['subject'].'",
         "commentAttachment":"'.$foundArray[0]['commentAttachment'].'",
         "comments":"'.add_slashes(HtmlFunctions::getInstance()->removePHPJS($foundArray[0]['comments'])).'",
         "postedOn":"'.$foundArray[0]['postedOn'].'"}';
    }
    else {
        echo 0;
    }
}
// $History: scAjaxGetComments.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/04/08   Time: 12:50p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Added the functionality to view uploaded attachments sent along
//with messages to students and parents
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/13/08    Time: 2:38p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>