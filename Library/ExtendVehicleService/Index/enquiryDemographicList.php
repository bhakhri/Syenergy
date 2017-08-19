<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices  and show it in dashboard
//
// Author : Rajeev Aggarwal
// Created on : (13.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

	/**/
	global $sessionHandler;
 
		$studentTotalArray = $dashboardManager->getTotalStudentEnquiry();
		$curDate  = date('Y-m-d');
		$studentTotalTodayArray = $dashboardManager->getTotalStudentEnquiry(" WHERE enquiryDate= '$curDate'");

		$studentTotalConsularArray = $dashboardManager->getTotalConsular();
		
		$studentTotalMaleArray = $dashboardManager->getStudentEnquiryGenderRecord(" AND studentGender = 'M'");
		$studentTotalFeMaleArray = $dashboardManager->getStudentEnquiryGenderRecord(" AND studentGender = 'F'");
		
	 
		/* START: function to fetch student enquiry city list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryCityList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){
	
			$totalCount = $studentRecordArray[$i]['foundCity'];
			if($studentRecordArray[$i]['notfoundCity'])
				$totalCount = $studentRecordArray[$i]['notfoundCity'];
			$strList .= "<slice title='".$studentRecordArray[$i]['cityName']."' description='".$studentRecordArray[$i]['cityId']."~city'>".$totalCount."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryCityData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry  city list */

		/* START: function to fetch student enquiry state list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryStateList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){
			
			$totalCount = $studentRecordArray[$i]['foundState'];
			if($studentRecordArray[$i]['notfoundState'])
				$totalCount = $studentRecordArray[$i]['notfoundState'];

			$strList .= "<slice title='".$studentRecordArray[$i]['stateName']."' description='".$studentRecordArray[$i]['stateId']."~state'>".$totalCount."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryStateData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry state list */

		/* START: function to fetch student enquiry degree list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryDegreeList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){

			$strList .= "<slice title='".$studentRecordArray[$i]['className']."' description='".$studentRecordArray[$i]['classId']."~class'>".$studentRecordArray[$i]['totalCount']."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryDegreeData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry degree list */

		/* START: function to fetch student enquiry consoler list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryConsolerList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){

			$strList .= "<slice title='".$studentRecordArray[$i]['userName']."' description='".$studentRecordArray[$i]['addedByUserId']."~consoler'>".$studentRecordArray[$i]['totalCount']."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryConsolerData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry consoler list */

		/* START: function to fetch student enquiry gender list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryGenderList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){

			$studentGender = "Male";
			if($studentRecordArray[$i]['studentGender']=='F'){
			
				$studentGender = "Female";
			}
			$strList .= "<slice title='".$studentGender."' description='".$studentRecordArray[$i]['studentGender']."~gender'>".$studentRecordArray[$i]['totalCount']."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryGenderData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry gender list */
 
		
		

// $History: enquiryDemographicList.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/03/09    Time: 12:10p
//Created in $/LeapCC/Library/Index
//Intial checkin for student enquiry demographics for admin
?>