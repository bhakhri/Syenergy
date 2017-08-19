<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 17-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php"); 
require_once(MODEL_PATH . "/Management/StudentManager.inc.php");
define('MODULE','ManagementStudentInfo');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
$studentManager = StudentManager::getInstance();
$searchStudent = $REQUEST_DATA['searchStudent'];

/* START: function to fetch student branch data */
if($searchStudent=='Branch'){
	$strList = "";
	$branchRecordArray = $studentManager->getStudentBranchList();
	$cnt = count($branchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$branchRecordArray[$i]['branchName']."' description='".$branchRecordArray[$i]['branchId']."~branch'>".$branchRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBranchData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student branch data xml file");
	}
	echo "branch";
}
/* END: function to fetch student branch data */

/* START: function to fetch student degree data */
if($searchStudent=='Degree'){
	$strList = "";
	$degreeRecordArray = $studentManager->getStudentDegreeList();
	$cnt = count($degreeRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$degreeRecordArray[$i]['degreeName']."' description='".$degreeRecordArray[$i]['degreeId']."~degree'>".$degreeRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentDegreeData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student degree data xml file");
	}
	echo "degree";
}
/* END: function to fetch student degree data */

/* START: function to fetch student batch data */
if($searchStudent=='Batch'){
	$strList ="";
	$batchRecordArray = $studentManager->getStudentBatchList();
	$cnt = count($batchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$batchRecordArray[$i]['batchName']."' description='".$batchRecordArray[$i]['batchId']."~batch'>".$batchRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBatchData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student batch data xml file");
	}
	echo "batch";
}
/* END: function to fetch student batch data */

/* START: function to fetch student study period data */
if($searchStudent=='StudyPeriod'){
	$strList ="";
	$batchRecordArray = $studentManager->getStudentStudyPeriodList();
	$cnt = count($batchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$batchRecordArray[$i]['periodName']."' description='".$batchRecordArray[$i]['studyPeriodId']."~studyperiod'>".$batchRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentStudyPeriodData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student study period data xml file");
	}
	echo "studyperiod";
}
/* END: function to fetch student study period data */

/* START: function to fetch student category data */
if($searchStudent=='Category'){
	$strList ="";
	$studentQuotaRecordArray = $studentManager->getStudentQuotaList();
	$cnt = count($studentQuotaRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentQuotaRecordArray[$i]['quotaName'].'-'.$studentQuotaRecordArray[$i]['quotaAbbr']."' description='".$studentQuotaRecordArray[$i]['quotaId']."~quota'>".$studentQuotaRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentQuotaData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student Category data xml file");
	}
	echo "category";
}
/* END: function to fetch student category data */

/* START: function to fetch student hostel data */
if($searchStudent=='Hostel'){
	$strList ="";
	$hostelRecordArray = $studentManager->getStudentHostelList(" AND hostelId IS NULL");
	 
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
	$strList .= "<slice title='Day Scholar' description='0~hostel'>".$hostelRecordArray[0]['totalCount']."</slice>\n";

	$hostelRecordArray = $studentManager->getStudentHostelList(" AND hostelId IS NOT NULL");
	$strList .= "<slice title='Hostel' description='1~hostel'>".$hostelRecordArray[0]['totalCount']."</slice>\n";

	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentHostelData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student hostel data xml file");
	}

	echo "hostel";
}
/* END: function to fetch student hostel data */

/* START: function to fetch student hostel detail data */
if($searchStudent=='HostelDetail'){

	$strList ="";
	$hostelDetailRecordArray = $studentManager->getStudentHostelDetailList();
	$cnt = count($hostelDetailRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$hostelDetailRecordArray[$i]['hostelName']."' description='".$hostelDetailRecordArray[$i]['hostelId']."~hosteldetail'>".$hostelDetailRecordArray[$i]['totalCount']."</slice>\n";
    } 
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentHostelDetailData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student hostel detail data xml file");
	}
	echo "hosteldetail";
}
/* END: function to fetch student hostel detail data */

/* START: function to fetch student city data */
if($searchStudent=='City'){
	 
	$studentRecordArray = $studentManager->getStudentCityList();
	$cnt = count($studentRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentRecordArray[$i]['cityName']."' description='".$studentRecordArray[$i]['cityId']."~city'>".$studentRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentCityData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student city data xml file");
	}
	echo "city";
}
/* END: function to fetch student city data */

/* START: function to fetch student state data */
if($searchStudent=='State'){

	$strList ="";
	$studentStateRecordArray = $studentManager->getStudentStateList();
	$cnt = count($studentStateRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentStateRecordArray[$i]['stateName']."' description='".$studentStateRecordArray[$i]['stateId']."~state'>".$studentStateRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentStateData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student state data xml file");
	}
	echo "state";
}
/* END: function to fetch student state data */

/* START: function to fetch student nationality data */
if($searchStudent=='Nationality'){

	$nationalityRecordArray = $studentManager->getStudentNationalityList();
	$cnt = count($nationalityRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {
		
		//$natioanlity = $nationalityRecordArray[$i]['countryId']==''?0:$nationalityRecordArray[$i]['countryId'];
		$strList .= "<slice title='".$nationalityRecordArray[$i]['countryName']."' description='".$nationalityRecordArray[$i]['countryId']."~nationality'>".$nationalityRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentNationalityData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student nationality data xml file");
	}
	echo "nationality";
}
/* END: function to fetch student nationality data */

/* START: function to fetch student bus route data */
if($searchStudent=='BusRoute'){

	$strList ="";
	$studentRouteRecordArray = $studentManager->getStudentBusRouteList();
	$cnt = count($studentRouteRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentRouteRecordArray[$i]['routeName'].'('.$studentRouteRecordArray[$i]['routeCode'].')'."' description='".$studentRouteRecordArray[$i]['busRouteId']."~busroute'>".$studentRouteRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBusRouteData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student bus route data xml file");
	}
	echo "busroute";
}
/* END: function to fetch student bus route data */

/* START: function to fetch student bus stop data */
if($searchStudent=='BusStop'){

	$strList ="";
	$studentStopRecordArray = $studentManager->getStudentBusStopList();
	$cnt = count($studentStopRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentStopRecordArray[$i]['stopAbbr']."' description='".$studentStopRecordArray[$i]['busStopId']."~busstop'>".$studentStopRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBusStopData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student bus stop data xml file");
	}
	echo "busstop";
}
/* END: function to fetch student bus stop data */

/* START: function to fetch student Gender data */
if($searchStudent=='Gender'){

	$genderRecordArray = $studentManager->getStudentGenderList(" AND studentGender ='M'");
	$cnt = count($genderRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Male' description='M~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$genderRecordArray = $studentManager->getStudentGenderList(" AND studentGender ='F'");
	$cnt = count($genderRecordArray);
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Female' description='F~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentGenderData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student Gender data xml file");
	}
	echo "gender";
}
/* END: function to fetch student gender data */

/* START: function to fetch student bus route data */
if($searchStudent=='Institute'){

	$strList ="";
	$instituteRecordArray = $studentManager->getStudentInstituteList();
	$cnt = count($instituteRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$instituteRecordArray[$i]['instituteName']."' description='".$instituteRecordArray[$i]['instituteId']."~institute'>".$instituteRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentInstituteData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student bus route data xml file");
	}
	echo "institute";
}
/* END: function to fetch student bus route data */



//$History: getStudentGraph.php $
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