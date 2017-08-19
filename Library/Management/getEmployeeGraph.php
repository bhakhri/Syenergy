<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php"); 
require_once(MODEL_PATH . "/Management/EmployeeManager.inc.php");
define('MODULE','ManagementEmployeeInfo');
define('ACCESS','view'); 
UtilityManager::ifManagementNotLoggedIn(); 
$employeeManager = EmployeeManager::getInstance();

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

/* START: function to fetch employee teaching data */
if($searchStudent=='Teaching'){
	$strList = "";
	$teachingRecordArray = $employeeManager->getEmployeeTeachingList(" AND isTeaching =1 ");
	$cnt = count($teachingRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Teaching Employee' description='1~Teaching'>".$teachingRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$teachingRecordArray = $employeeManager->getEmployeeTeachingList(" AND isTeaching!=1 ");
	$cnt = count($teachingRecordArray);
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Non-Teaching Employee' description='0~Teaching'>".$teachingRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeTeachingData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee teaching data xml file");
	}
	echo "teaching";
}
/* END: function to fetch employee teaching data */

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

/* START: function to fetch employee city data */
if($searchStudent=='City'){

	$strList ="";
	$employeeCityRecordArray = $employeeManager->getEmployeeCityList();
	$cnt = count($employeeCityRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$employeeCityRecordArray[$i]['cityName'].'('.$employeeCityRecordArray[$i]['cityCode'].")' description='".$employeeCityRecordArray[$i]['cityId']."~city'>".$employeeCityRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeCityData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee city data xml file");
	}
	echo "city";
}
/* END: function to fetch employee city data */

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

/* START: function to fetch employee designation data */
if($searchStudent=='Designation'){
	$strList ="";
	$designationRecordArray = $employeeManager->getEmployeeDesignationList();
	$cnt = count($designationRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$designationRecordArray[$i]['designationName']."' description='".$designationRecordArray[$i]['designationId']."~designation'>".$designationRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeDesignationData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student employee designation xml file");
	}

	echo "designation";
}
/* END: function to fetch employee designation data */

/* START: function to fetch employee branch  data */
if($searchStudent=='Branch'){

	$strList ="";
	$branchRecordArray = $employeeManager->getEmployeeBranchList();
	$cnt = count($branchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$branchRecordArray[$i]['branchCode'].'-'.$branchRecordArray[$i]['branchName']."' description='".$branchRecordArray[$i]['branchId']."~branch'>".$branchRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeBranchData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee branch data xml file");
	}
	echo "branch";
}
/* END: function to fetch employee branch  data */

/* START: function to fetch employee gender  data */
if($searchStudent=='Gender'){
	 
	$strList = "";
	$genderRecordArray = $employeeManager->getEmployeeGenderList(" AND gender ='M'");
	$cnt = count($genderRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Male'  description='M~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$genderRecordArray = $employeeManager->getEmployeeGenderList(" AND gender ='F'");
	$cnt = count($genderRecordArray);
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Female' description='F~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeGenderData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee gender  data xml file");
	}
	echo "gender";
}
/* END: function to fetch employee gender data */
//$History: getEmployeeGraph.php $
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