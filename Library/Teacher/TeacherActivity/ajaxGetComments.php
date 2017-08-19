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
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','ListTeacherCommentMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['commentId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $foundArray = TeacherManager::getInstance()->getComments($REQUEST_DATA['commentId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {
         
         $foundArray[0]['comments']=add_slashes(HtmlFunctions::getInstance()->removePHPJS($foundArray[0]['comments']));  
         $foundArray[0]['postedOn']=UtilityManager::formatDate($foundArray[0]['postedOn'],true);
         echo json_encode($foundArray[0]);
        /*
        echo ' 
         {"commentId":"'.$foundArray[0]['commentId'].'",
         "subject":"'.$foundArray[0]['subject'].'",
         "comments":"'.add_slashes(HtmlFunctions::getInstance()->removePHPJS($foundArray[0]['comments'])).'",
         "postedOn":"'.$foundArray[0]['postedOn'].'"}';
        */ 
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetComments.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/02/10   Time: 12:20
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added the feature :
//Display Teacher Comments:By Default it should show the list of message
//sent by respective employee. after that search filter can be applied
//which are currently mandatory
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/13/08    Time: 2:38p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>