<?php
//-------------------------------------------------------
// This File is used for fetching labels for 
// Author :Dipanjan Bhattacharjee
// Created on : 15.01.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    $labelId = trim($REQUEST_DATA['labelId']);
    
    if($timeTableLabelId=='' or $labelId==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $finalReportManager = FeedBackReportAdvancedManager::getInstance();
	$finalArray = $finalReportManager->getSelectedTimeTableLabelCategories($timeTableLabelId,$labelId);
	echo json_encode($finalArray);

// $History: ajaxInitGetSurveyLabels.php $
?>