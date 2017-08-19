<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE subject drop down[class centric]
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['classId'])!= '') {
    	
	require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
	$teacherManager = AdminTasksManager::getInstance();
    
	$foundArray = $teacherManager->getTransferredSubjects(' AND tm.classId='.$REQUEST_DATA['classId']);

	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxAllSubjectPopulate.php $
?>