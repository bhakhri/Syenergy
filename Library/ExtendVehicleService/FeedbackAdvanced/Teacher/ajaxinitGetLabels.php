<?php
//-------------------------------------------------------
//  This File is used for fetching labels for 
// Author :Dipanjan Bhattacharjee
// Created on : 23.03.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    if($timeTableLabelId==''){
        echo 'Required Parameters Missing';
        die;
    }
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
    
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $questionMappingAdvancedManager = FeedBackReportAdvancedManager::getInstance();
	$labelArray = $questionMappingAdvancedManager->getFeedbackLabelsForTeachers(' AND f.timeTableLabelId='.$timeTableLabelId.' AND fa.employeeId='.$employeeId);
    if(is_array($labelArray) and count($labelArray)>0){
	  echo json_encode($labelArray);
    }
    else{
        echo 0;
    }

// $History: ajaxinitGetLabels.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/03/10   Time: 11:09
//Created in $/LeapCC/Library/FeedbackAdvanced/Teacher
//Created Feedback Teacher Detailed GPA Report (Advanced) for Teacher
//login
?>