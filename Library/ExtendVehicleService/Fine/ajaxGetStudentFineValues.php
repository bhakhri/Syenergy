<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fine Category LIST
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['fineStudentId'] ) != '') {

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $foundArray = FineManager::getInstance()->getFineStudent(' AND fineStudentId="'.$REQUEST_DATA['fineStudentId'].'"');

	$foundArray1 = FineManager::getInstance()->getRoleFineCategoryData("  AND s.rollNo='".trim(add_slashes($REQUEST_DATA['rollNo']))."' AND rfi.instituteId='".trim(add_slashes($foundArray[0]['instituteId']))."' ");
	
	$finalArray=array($foundArray,$foundArray1);
    if(is_array($foundArray) && count($foundArray)>0 ) {  

        echo json_encode($finalArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetStudentFineValues.php $
//
//*****************  Version 1  *****************
/// Author : Saurabh Thukral
// Created on : (27.07.2012)
//Created in $/LeapCC/Library/Fine
//Intial checkin for fine student
?>