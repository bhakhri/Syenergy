<?php
//-------------------------------------------------------
//  This File is used for fetching labels for 
//
//
// Author :Gurkeerat Sidhu
// Created on : 15.01.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    //require_once(MODEL_PATH . "/FeedBackQuestionMappingAdvancedManager.inc.php");
    //$questionMappingAdvancedManager = FeedBackQuestionMappingAdvancedManager::getInstance();
	//$labelArray = $questionMappingAdvancedManager->getSelectedTimeTableLabel($timeTableLabelId);
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $finalReportManager = FeedBackReportAdvancedManager::getInstance();
    $finalArray = $finalReportManager->getSelectedTimeTableLabel($timeTableLabelId,trim($REQUEST_DATA['type']));
    echo json_encode($finalArray);

// $History: ajaxinitGetLabels.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/19/10    Time: 3:07p
//Created in $/LeapCC/Library/FeedbackAdvanced
//created file under feedback module
//

?>