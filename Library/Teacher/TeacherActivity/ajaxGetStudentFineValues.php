<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fine Category LIST
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['fineStudentId'] ) != '') {

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $foundArray = FineManager::getInstance()->getFineStudent(' AND fineStudentId="'.$REQUEST_DATA['fineStudentId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  

        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetStudentFineValues.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:21p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:30p
//Created in $/LeapCC/Library/Fine
//Intial checkin for fine student
?>