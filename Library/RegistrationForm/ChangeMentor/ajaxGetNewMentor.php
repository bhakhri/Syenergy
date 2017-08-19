<?php
//-------------------------------------------------------
// THIS FILE IS USED TO fetch previous class of a class
// Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ChangeMentor');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['currentMentorId'] ) != '') {
   
     require_once(MODEL_PATH . "/RegistrationForm/ChangeMentorManager.inc.php");
    $changeMentorManager = ChangeMentorManager::getInstance();
    
    $foundArray = $changeMentorManager->getNewMentorNew(trim($REQUEST_DATA['currentMentorId']));
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetPreviousClass.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/12/09   Time: 19:15
//Created in $/LeapCC/Library/Group
//Done group coping module
?>
