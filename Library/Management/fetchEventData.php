<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ManagementEventInfo');
    define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();
            

	/* START: function to fetch notice data */
	$eventRecordArray = $dashboardManager->getMonthWiseEventList();
	$cnt = count($eventRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {
		$eventMonth = "";
		if($eventRecordArray[$i]['eventMonth']<10)
			$eventMonth = "0".$eventRecordArray[$i]['eventMonth'];
		else
			$eventMonth = $eventRecordArray[$i]['eventMonth'];
		$strList .= "<slice title='".$monArr[$eventMonth]."' description='".$eventMonth."'>".$eventRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/eventMonthwiseData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open user activity data xml file");
	}
    
		
	/* END: function to fetch student city data */

// $History: fetchEventData.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Management
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>