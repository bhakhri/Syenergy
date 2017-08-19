<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE message div
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (18.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
    
if(trim($REQUEST_DATA['messageId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $foundArray = ScTeacherManager::getInstance()->getAdminMessageList(" AND adm.messageId=".$REQUEST_DATA['messageId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        //echo json_encode($foundArray[0]);
        echo   '{"messageId":"'.$foundArray[0]['messageId'].'","userName":"'.$foundArray[0]['userName'].'","subject":"'.$foundArray[0]['subject'].'","message":"'.add_slashes(HtmlFunctions::getInstance()->removePHPJS(str_replace("\n",'',$foundArray[0]['message']))).'","dated":"'.$foundArray[0]['dated'].'"}';
    }
    else {
        echo 0;
    }
}
// $History: scAjaxGetMessageDetails.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>