<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Student Offence Remarks
//
//
// Author : Parveen Sharma
// Created on : 29-05-2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
 require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
 $studentManager = StudentReportsManager::getInstance();    
 
if(trim($REQUEST_DATA['disciplineId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
 
    $foundArray = $studentManager->getOffenceDetail(" WHERE disciplineId = ".trim($REQUEST_DATA['disciplineId']));
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo $foundArray[0]['remarks'];
    }
}
// $History: ajaxGetOffenseDetails.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 4:15p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin 
//

?>