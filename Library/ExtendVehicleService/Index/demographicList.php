<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices  and show it in dashboard
//
// Author : Rajeev Aggarwal
// Created on : (22.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	
	/* START: function to fetch student branch list */
	$strList ="";
	$branchRecordArray = $dashboardManager->getStudentBranchList();
	$cnt = count($branchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$branchRecordArray[$i]['branchCode']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";

	for($k=1;$k<2;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' url='javascript:showData(".$branchRecordArray[$i]['branchId'].")'>".$branchRecordArray[$i]['totalCount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBranchBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	/* END: function to fetch student branch list */

	/* START: function to fetch student degree list */
	$strList ="";
	$degreeRecordArray = $dashboardManager->getStudentDegreeList();
	$cnt = count($degreeRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$degreeRecordArray[$i]['degreeName']."' description='".$degreeRecordArray[$i]['degreeId']."~degree'>".$degreeRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentDegreeData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	/* END: function to fetch student degree list */

	/* START: function to fetch student study period list */
	$strList ="";
	$batchRecordArray = $dashboardManager->getStudentStudyPeriodList();
	$cnt = count($batchRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$batchRecordArray[$i]['periodName']."' description='".$batchRecordArray[$i]['studyPeriodId']."~studyperiod'>".$batchRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentStudyPeriodData.xml";
	
	UtilityManager::writeXML($strList, $xmlFilePath);
	/* END: function to fetch student study period list */

	/* START: function to fetch student city list */
	$strList = "";
	$studentRecordArray = $dashboardManager->getStudentCityList();
	$cnt = count($studentRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++){

		$strList .= "<slice title='".$studentRecordArray[$i]['cityName']."' description='".$studentRecordArray[$i]['cityId']."~city'>".$studentRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentCityData.xml";
	
	UtilityManager::writeXML($strList, $xmlFilePath);
	/* END: function to fetch student city list */

	/* START: function to fetch student gender list */
	$strList = "";
	$genderRecordArray = $dashboardManager->getStudentGenderList(" AND studentGender ='M'");
	$cnt = count($genderRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Male' description='M~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$genderRecordArray = $dashboardManager->getStudentGenderList(" AND studentGender ='F'");
	$cnt = count($genderRecordArray);
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='Female' description='F~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

	$strList .="</pie>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/studentGenderData.xml";
	
	UtilityManager::writeXML($strList, $xmlFilePath);
	/* END: function to fetch student gender list */
  

// $History: demographicList.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/24/08   Time: 4:26p
//Updated in $/LeapCC/Library/Index
//Updated Print reports for management role
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 1:42p
//Created in $/LeapCC/Library/Index
//Intial checkin
?>