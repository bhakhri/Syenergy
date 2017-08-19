<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Jaineesh
// Created on : 19-Jan-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoleWiseList');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    /////////////////////////
    
    if(trim($REQUEST_DATA['userId'] ) != '') {
		//echo($REQUEST_DATA['roleView']);
		$displayMonth = $REQUEST_DATA['roleView'];
		$userId = $REQUEST_DATA['userId'];
    
    //$totalArray = $studentReportsManager->getTotalUserData($roleId);
	if ($displayMonth == 3) {
		$threeMonthPreviousDay = $studentReportsManager->getUserThreeMonthsLoginData();
		$threeMonthPreviousDay = $threeMonthPreviousDay[0]['previousDay'];
		$pastDateTS = strtotime($threeMonthPreviousDay);

		for ($currentDateTS = $pastDateTS; $currentDateTS <= strtotime("now"); $currentDateTS += (60 * 60 * 24)) 
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr[]=date("d-m-Y",$currentDateTS);
		}
		
		$loginMonthsRecordArray = $studentReportsManager->getUserThreeLoginData($userId);
		$cnt = count($loginMonthsRecordArray);
		for ($i=0;$i<$cnt;$i++) {
			$loginArray[$loginMonthsRecordArray[$i][loggedInTime]] = $loginMonthsRecordArray[$i][totalCount];
		}
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

		$strList .="<series>\n";

		for($i=0;$i<count($currentDateStr);$i++) {
				
				$strList .= "<value xid='".$currentDateStr[$i]."'>".$currentDateStr[$i]."</value>\n";
			}
		
		$strList .="</series>\n";
		$strList .="<graphs>\n<graph gid='2'>";

		foreach($currentDateStr as $dayWiseDate){
			
			$loginCount = $loginArray[$dayWiseDate];
			if($loginCount == "") {
				$loginCount = 0;
			}
			$strList .= "<value xid='".$dayWiseDate."' bullet='round' >".$loginCount."</value>\n";
				//$dayWiseDate = $dayWiseDate.'--'.$loginArray[$dayWiseDate];
			}

		$strList .="</graph></graphs>\n";
		$strList .="</chart>";

		$xmlFilePath = TEMPLATES_PATH."/Xml/loginDetailBarData.xml";
		if(is_writable($xmlFilePath)){

			$handle = @fopen($xmlFilePath, 'w');
			if (@fwrite($handle, $strList) === FALSE){
				die("unable to write");
			}
		}
		else{
			logError("unable to open user activity data xml file");
		}
		
		echo SUCCESS;

		
	}

	
	else if ($displayMonth == 6) {
		$sixMonthPreviousDay = $studentReportsManager->getUserSixMonthsLoginData();
		$sixMonthPreviousDay = $sixMonthPreviousDay[0]['previousDay'];
		$pastDateTS = strtotime($sixMonthPreviousDay);

		for ($currentDateTS = $pastDateTS; $currentDateTS <= strtotime("now"); $currentDateTS += (60 * 60 * 24)) 
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr[]=date("d-m-Y",$currentDateTS);
		}


		$loginMonthsRecordArray = $studentReportsManager->getUserSixLoginData($userId);
		$cnt = count($loginMonthsRecordArray);

		for ($i=0;$i<$cnt;$i++) {
			$loginArray[$loginMonthsRecordArray[$i][loggedInTime]] = $loginMonthsRecordArray[$i][totalCount];
		}
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

		$strList .="<series>\n";

		for($i=0;$i<count($currentDateStr);$i++) {
				
				$strList .= "<value xid='".$currentDateStr[$i]."'>".$currentDateStr[$i]."</value>\n";
			}
		
		$strList .="</series>\n";
		$strList .="<graphs>\n<graph gid='2'>";

		foreach($currentDateStr as $dayWiseDate){
			
			$loginCount = $loginArray[$dayWiseDate];
			if($loginCount == "") {
				$loginCount = 0;
			}
			$strList .= "<value xid='".$dayWiseDate."' bullet='round' >".$loginCount."</value>\n";
				//$dayWiseDate = $dayWiseDate.'--'.$loginArray[$dayWiseDate];
			}

		$strList .="</graph></graphs>\n";
		$strList .="</chart>";

		$xmlFilePath = TEMPLATES_PATH."/Xml/loginDetailBarData.xml";
		if(is_writable($xmlFilePath)){

			$handle = @fopen($xmlFilePath, 'w');
			if (@fwrite($handle, $strList) === FALSE){
				die("unable to write");
			}
		}
		else{
			logError("unable to open user activity data xml file");
		}
		echo SUCCESS;

		
		}

	
	else if ($displayMonth == 9) {
		
		$nineMonthPreviousDay = $studentReportsManager->getUserNineMonthsLoginData();
		$nineMonthPreviousDay = $nineMonthPreviousDay[0]['previousDay'];
		$pastDateTS = strtotime($nineMonthPreviousDay);

		for ($currentDateTS = $pastDateTS; $currentDateTS <= strtotime("now"); $currentDateTS += (60 * 60 * 24)) 
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr[]=date("d-m-Y",$currentDateTS);
		}

		$loginMonthsRecordArray = $studentReportsManager->getUserNineLoginData($userId);
		$cnt = count($loginMonthsRecordArray);

		for ($i=0;$i<$cnt;$i++) {
			$loginArray[$loginMonthsRecordArray[$i][loggedInTime]] = $loginMonthsRecordArray[$i][totalCount];
		}
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

		$strList .="<series>\n";

		for($i=0;$i<count($currentDateStr);$i++) {
				
				$strList .= "<value xid='".$currentDateStr[$i]."'>".$currentDateStr[$i]."</value>\n";
			}
		
		$strList .="</series>\n";
		$strList .="<graphs>\n<graph gid='2'>";

		foreach($currentDateStr as $dayWiseDate){
			
			$loginCount = $loginArray[$dayWiseDate];
			if($loginCount == "") {
				$loginCount = 0;
			}
			$strList .= "<value xid='".$dayWiseDate."' bullet='round' >".$loginCount."</value>\n";
				//$dayWiseDate = $dayWiseDate.'--'.$loginArray[$dayWiseDate];
			}

		$strList .="</graph></graphs>\n";
		$strList .="</chart>";

		$xmlFilePath = TEMPLATES_PATH."/Xml/loginDetailBarData.xml";
		if(is_writable($xmlFilePath)){

			$handle = @fopen($xmlFilePath, 'w');
			if (@fwrite($handle, $strList) === FALSE){
				die("unable to write");
			}
		}
		else{
			logError("unable to open user activity data xml file");
		}
		echo SUCCESS;


		}

	else if ($displayMonth == 1) {
		$oneYearPreviousDay = $studentReportsManager->getUserOneYearLoginData();
		$oneYearPreviousDay = $oneYearPreviousDay[0]['previousDay'];
		$pastDateTS = strtotime($oneYearPreviousDay);

		for ($currentDateTS = $pastDateTS; $currentDateTS <= strtotime("now"); $currentDateTS += (60 * 60 * 24)) 
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr[]=date("d-m-Y",$currentDateTS);
		}

		$loginMonthsRecordArray = $studentReportsManager->getUserYearLoginData($userId);
		$cnt = count($loginMonthsRecordArray);
		for ($i=0;$i<$cnt;$i++) {
			$loginArray[$loginMonthsRecordArray[$i][loggedInTime]] = $loginMonthsRecordArray[$i][totalCount];
		}
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

		$strList .="<series>\n";

		for($i=0;$i<count($currentDateStr);$i++) {
				
				$strList .= "<value xid='".$currentDateStr[$i]."'>".$currentDateStr[$i]."</value>\n";
			}
		
		$strList .="</series>\n";
		$strList .="<graphs>\n<graph gid='2'>";

		foreach($currentDateStr as $dayWiseDate){
			
			$loginCount = $loginArray[$dayWiseDate];
			if($loginCount == "") {
				$loginCount = 0;
			}
			$strList .= "<value xid='".$dayWiseDate."' bullet='round' >".$loginCount."</value>\n";
				//$dayWiseDate = $dayWiseDate.'--'.$loginArray[$dayWiseDate];
			}

		$strList .="</graph></graphs>\n";
		$strList .="</chart>";

		$xmlFilePath = TEMPLATES_PATH."/Xml/loginDetailBarData.xml";
		if(is_writable($xmlFilePath)){

			$handle = @fopen($xmlFilePath, 'w');
			if (@fwrite($handle, $strList) === FALSE){
				die("unable to write");
			}
		}
		else{
			logError("unable to open user activity data xml file");
		}
		echo SUCCESS;


		}

	}


	
    
// $History: populateThreeLogin.php $

//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Library/StudentReports
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/28/09    Time: 6:12p
//Updated in $/LeapCC/Library/StudentReports
//modification in files to run role wise graphs & report in leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/28/09    Time: 4:40p
//Created in $/LeapCC/Library/StudentReports
//copy from sc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/15/09    Time: 3:58p
//Updated in $/Leap/Source/Library/ScStudentReports
//modified in showing the graph
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Library/ScStudentReports
//modified in feedback label & role wise graph
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/10/09    Time: 6:46p
//Created in $/Leap/Source/Library/ScStudentReports
//new ajax files to show the graph
//
//

?>
