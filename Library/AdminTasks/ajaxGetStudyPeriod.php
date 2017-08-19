<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE group drop down[class centric]
//
//
// Author : Parveen Sharma
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['degree'])!= '' and trim($REQUEST_DATA['degree'])!= '') {
	
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonManager = CommonQueryManager::getInstance();
	
    $arr = explode('-',$REQUEST_DATA['degree']);
     
    $condition = " AND universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND  cls.isActive=1";
	$foundArray = $commonManager->getScClassStudyPeriod('periodName',$condition);
	
	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}

// $History: ajaxGetStudyPeriod.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/04/09   Time: 6:56p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>