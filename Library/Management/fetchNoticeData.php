<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ManagementNoticeInfo');
    define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();
            

	/* START: function to fetch notice data */
	$noticeRecordArray = $dashboardManager->getNoticeMonthWiseList();
	$cnt = count($noticeRecordArray);
	 
	$arrayNotice = array();
    for($i=0;$i<$cnt;$i++) {

		 $arrayNotice[]=($noticeRecordArray[$i]['visibleMonth']);
    } 
	 
    $noticeArraycount =array_count_values($arrayNotice);
	 
	$cnt = count($noticeArraycount);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    foreach($noticeArraycount as $key=>$value) {

		$noticeMonth = "";
		if($key<10)
			$noticeMonth = "0".$key;
		else
			$noticeMonth = $key;

		$strList .= "<slice title='".$monArr[$noticeMonth]."' description='".$noticeMonth."'>".$value."</slice>\n";
		 
    }  
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/noticeMonthwiseData.xml";
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

// $History: fetchNoticeData.php $
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