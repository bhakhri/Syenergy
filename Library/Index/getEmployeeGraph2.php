<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/EmployeeManager.inc.php");
$employeeManager = EmployeeManager::getInstance();
//define('MODULE','UploadEmployeeDetail');
//define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
$searchStudent = $REQUEST_DATA['searchStudent'];

/* START: function to fetch employee role data */
if($searchStudent=='RoleWise'){

	$strList ="";     
	$employeeRecordArray = $employeeManager->getEmployeeRoleList();
	$cnt = count($employeeRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$employeeRecordArray[$i]['roleName']."' description='".$employeeRecordArray[$i]['roleId']."~role'>".$employeeRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeRoleWiseData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee role data xml file");
	}
	echo "rolewise";
}
/* END: function to fetch employee role data */



/* START: function to fetch employee marital data */
if($searchStudent=='Marital'){

	$strList = "";
	$maritalRecordArray = $employeeManager->getEmployeeMaritalList();
	$cnt = count($maritalRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$maritalRecordArray[$i]['maritalStatus']."' description='".$maritalRecordArray[$i]['isMarried']."~marital'>".$maritalRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeMaritalData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee marital data xml file");
	}
	echo "marital";
}
/* END: function to fetch employee marital data */



/* START: function to fetch employee state data */
if($searchStudent=='State'){

	$strList ="";
	$stateDetailRecordArray = $employeeManager->getEmployeeStateList();
	$cnt = count($stateDetailRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$stateDetailRecordArray[$i]['stateName'].'('.$stateDetailRecordArray[$i]['stateCode'].")' description='".$stateDetailRecordArray[$i]['stateId']."~state'>".$stateDetailRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeStateDetailData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee state data xml file");
	}
	echo "state";
}
/* END: function to fetch employee state data */




//$History: getEmployeeGraph2.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 11/26/09   Time: 1:04p
//Created in $/LeapCC/Library/Index
//added file related to 'employee export/import' module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>