<?php
//This file sends the data, creates the graph on runtime
//
// Author :Rajeev Aggarwal
// Created on : 22-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/DashBoardManager.inc.php");
$dashboardManager = DashBoardManager::getInstance();


$searchStudent = $REQUEST_DATA['searchStudent'];

/* START: function to fetch student batch data */
if($searchStudent=='Batch'){
	$strList ="";
	$batchRecordArray = $dashboardManager->getStudentBatchList();
	$cnt = count($batchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$batchRecordArray[$i]['batchName']."' description='".$batchRecordArray[$i]['batchId']."~batch'>".$batchRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBatchData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "batch";
}
/* END: function to fetch student batch data */

/* START: function to fetch student category data */
if($searchStudent=='Category'){
	$strList ="";
	$studentQuotaRecordArray = $dashboardManager->getStudentQuotaList();
	$cnt = count($studentQuotaRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentQuotaRecordArray[$i]['quotaName'].'-'.$studentQuotaRecordArray[$i]['quotaAbbr']."' description='".$studentQuotaRecordArray[$i]['quotaId']."~quota'>".$studentQuotaRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentQuotaData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "category";
}
/* END: function to fetch student category data */

/* START: function to fetch student hostel data */
if($searchStudent=='Hostel'){
	$strList ="";
	$hostelRecordArray = $dashboardManager->getStudentHostelList(" AND hostelId IS NULL");
	
	$totalCount = "";
	if($hostelRecordArray[0]['totalCount']=='')
		$totalCount = 0;
	else
		$totalCount = $hostelRecordArray[0]['totalCount'];
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
	$strList .= "<slice title='Day Scholar' description='0~hostel'>".$totalCount."</slice>\n";

	$hostelRecordArray = $dashboardManager->getStudentHostelList(" AND hostelId IS NOT NULL");

	$totalCount = "";
	if($hostelRecordArray[0]['totalCount']=='')
		$totalCount = 0;
	else
		$totalCount = $hostelRecordArray[0]['totalCount'];

	$strList .= "<slice title='Hostel' description='1~hostel'>".$totalCount."</slice>\n";

	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentHostelData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);

	echo "hostel";
}
/* END: function to fetch student hostel data */

/* START: function to fetch student state data */
if($searchStudent=='State'){

	$strList ="";
	$studentStateRecordArray = $dashboardManager->getStudentStateList();
	$cnt = count($studentStateRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentStateRecordArray[$i]['stateName']."' description='".$studentStateRecordArray[$i]['stateId']."~state'>".$studentStateRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentStateData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "state";
}
/* END: function to fetch student state data */

/* START: function to fetch student nationality data */
if($searchStudent=='Nationality'){

	$strList ="";
	$nationalityRecordArray = $dashboardManager->getStudentNationalityList();
	$cnt = count($nationalityRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {
		
		$strList .= "<slice title='".$nationalityRecordArray[$i]['countryName']."' description='".$nationalityRecordArray[$i]['countryId']."~nationality'>".$nationalityRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentNationalityData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "nationality";
}
/* END: function to fetch student nationality data */

/* START: function to fetch student bus route data */
if($searchStudent=='BusRoute'){

	$strList ="";
	$studentRouteRecordArray = $dashboardManager->getStudentBusRouteList();
	$cnt = count($studentRouteRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentRouteRecordArray[$i]['routeName'].'('.$studentRouteRecordArray[$i]['routeCode'].')'."' description='".$studentRouteRecordArray[$i]['busRouteId']."~busroute'>".$studentRouteRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBusRouteData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "busroute";
}
/* END: function to fetch student bus route data */

/* START: function to fetch student bus stop data */
if($searchStudent=='BusStop'){

	$strList ="";
	$studentStopRecordArray = $dashboardManager->getStudentBusStopList();
	$cnt = count($studentStopRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$studentStopRecordArray[$i]['stopAbbr']."' description='".$studentStopRecordArray[$i]['busStopId']."~busstop'>".$studentStopRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBusStopData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "busstop";
}
/* END: function to fetch student bus stop data */

/* START: function to fetch student Institute data */
if($searchStudent=='Institute'){

	$strList ="";
	$instituteRecordArray = $dashboardManager->getStudentInstituteList();
	$cnt = count($instituteRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$instituteRecordArray[$i]['instituteName']."' description='".$instituteRecordArray[$i]['instituteId']."~institute'>".$instituteRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentInstituteData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "institute";
}
/* END: function to fetch student Institute data */

//$History: getCustomGraph.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 1:42p
//Created in $/LeapCC/Library/Index
//Intial checkin
?>