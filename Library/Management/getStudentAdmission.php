<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 17-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Management/StudentAdmissionManager.inc.php");
define('MODULE','ManagementAdmissionInfo');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
$studentAdmissionManager = StudentAdmissionManager::getInstance();
$searchStudent = $REQUEST_DATA['searchStudent'];

/* START: function to fetch student branch data */
if($searchStudent=='Branch'){
	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctBranchYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	$branchRecordArray = $studentAdmissionManager->getDistinctBranch();
	$cnt = count($branchRecordArray);
	 
	for($i=0;$i<$cnt;$i++) {

		$strList .="\n\t<graph gid='".$branchRecordArray[$i]['branchCode']."' title='".$branchRecordArray[$i]['branchCode']."'>";
		$cnt1 = count($yearRecordArray);
		for($j=0;$j<$cnt1;$j++) {


			$condition = "AND br.branchCode='".$branchRecordArray[$i]['branchCode']."' AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";

			$totalArray = $studentAdmissionManager->getCountBranchYear($condition);

			$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~".$branchRecordArray[$i]['branchCode']."~branch\")'>".$totalArray[0]['totalRecords']."</value>";
		} 

		$strList .="\n\t</graph>"; 
		 
    } 

	$strList .="\n</graphs>\n</chart>";
	 
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBranchAdmission.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "branch";
}
/* END: function to fetch student branch data */

/* START: function to fetch student degree admission data */
if($searchStudent=='Degree'){
	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctDegreeYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	$branchRecordArray = $studentAdmissionManager->getDistinctDegree();
	$cnt = count($branchRecordArray);
	 
	for($i=0;$i<$cnt;$i++) {

		$strList .="\n\t<graph gid='".$branchRecordArray[$i]['degreeCode']."' title='".$branchRecordArray[$i]['degreeCode']."'>";
		$cnt1 = count($yearRecordArray);
		for($j=0;$j<$cnt1;$j++) {


			$condition = "AND deg.degreeCode='".$branchRecordArray[$i]['degreeCode']."' AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";

			$totalArray = $studentAdmissionManager->getCountDegreeYear($condition);

			//$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."'>".$totalArray[0]['totalRecords']."</value>";

			$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~".$branchRecordArray[$i]['degreeCode']."~degree\")'>".$totalArray[0]['totalRecords']."</value>";
		} 

		$strList .="\n\t</graph>"; 
		 
    } 

	$strList .="\n</graphs>\n</chart>";
	 
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentDegreeAdmission.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "degree";
}
/* END:  function to fetch student degree admission data */

/* START: function to fetch student batch admission data */
if($searchStudent=='Batch'){
	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctBatchYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	$branchRecordArray = $studentAdmissionManager->getDistinctBatch();
	$cnt = count($branchRecordArray);
	 
	for($i=0;$i<$cnt;$i++) {

		$strList .="\n\t<graph gid='".$branchRecordArray[$i]['batchName']."' title='".$branchRecordArray[$i]['batchName']."'>";
		$cnt1 = count($yearRecordArray);
		for($j=0;$j<$cnt1;$j++) {


			$condition = "AND bat.batchName='".$branchRecordArray[$i]['batchName']."' AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";

			$totalArray = $studentAdmissionManager->getCountBatchYear($condition);

			$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~".$branchRecordArray[$i]['batchName']."~batch\")'>".$totalArray[0]['totalRecords']."</value>";
		} 

		$strList .="\n\t</graph>"; 
		 
    } 

	$strList .="\n</graphs>\n</chart>";
	 
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBatchAdmission.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "batch";
}
/* END:  function to fetch student degree admission data */

/* START: function to fetch student category admission data */
if($searchStudent=='Category'){

	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctDegreeYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	$branchRecordArray = $studentAdmissionManager->getDistinctCategory();
	$cnt = count($branchRecordArray);
	 
	for($i=0;$i<$cnt;$i++) {

		$strList .="\n\t<graph gid='".$branchRecordArray[$i]['quotaAbbr']."' title='".$branchRecordArray[$i]['quotaAbbr']."'>";
		$cnt1 = count($yearRecordArray);
		for($j=0;$j<$cnt1;$j++) {


			$condition = "AND qta.quotaAbbr='".$branchRecordArray[$i]['quotaAbbr']."' AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";

			$totalArray = $studentAdmissionManager->getCountCategoryYear($condition);

			$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~".$branchRecordArray[$i]['quotaAbbr']."~category\")'>".$totalArray[0]['totalRecords']."</value>";
		} 

		$strList .="\n\t</graph>"; 
		 
    } 

	$strList .="\n</graphs>\n</chart>";
	 
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentCategoryAdmission.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "category";
}
/* END:  function to fetch student category admission data */

/* START: function to fetch student hostel admission data */
if($searchStudent=='Hostel'){

	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctDegreeYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	//$branchRecordArray = $studentAdmissionManager->getDistinctCategory();
	//$cnt = count($branchRecordArray);
	 
	 

	$strList .="\n\t<graph gid='Hostel' title='Hostel'>";
	$cnt1 = count($yearRecordArray);
	for($j=0;$j<$cnt1;$j++) {


		$condition = " AND stu.hostelId IS NOT NULL AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";

		$totalArray = $studentAdmissionManager->getCountHostelYear($condition);

		$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~hostel~hostel\")'>".$totalArray[0]['totalRecords']."</value>";
	} 

	$strList .="\n\t</graph>"; 

	$strList .="\n\t<graph gid='Day Scholar' title='Day Scholar'>";
	$cnt1 = count($yearRecordArray);
	for($j=0;$j<$cnt1;$j++) {


		$condition = " AND stu.hostelId IS NULL AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";

		$totalArray = $studentAdmissionManager->getCountHostelYear($condition);

		$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~day scholar~hostel\")'>".$totalArray[0]['totalRecords']."</value>";
	} 

	$strList .="\n\t</graph>"; 
		 
     

	$strList .="\n</graphs>\n</chart>";
	 
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentHostelAdmission.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "hostel";
}
/* END:  function to fetch student hostel admission data */

/* START: function to fetch student city data */
if($searchStudent=='City'){
	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	$cityRecordArray = $studentAdmissionManager->getCityAdmissionYear();
	$cnt = count($cityRecordArray);
	 
	$cityName = "";
	$yearName = "";
	for($i=0;$i<$cnt;$i++) {
		
		$cityName1 = $cityRecordArray[$i]['cityName'];
		$yearName1 = $cityRecordArray[$i]['yearAdmission'];
		$countRecords1 = $cityRecordArray[$i]['countRecords'];
	
		if($cityName == $cityName1){
			
			$strList .= "\t<value xid='".$yearName1."'>".$countRecords1."</value>\n";
		}
		else{
			
			$strList .= "\t</graph>\n<graph gid='".$cityName1."' title='".$cityName1."'>\n";
			$strList .= "\t<value xid='".$yearName1."'>".$countRecords1."</value>\n";
		}
		//$cityName1 = ($cityName == $cityName1)?$cityName1 = "": $cityName1;

		//$strList .=  $cityName1."--".$yearName1."--".$countRecords1."\n";
		 if($cityName1 != "")
			 $cityName = $cityName1;

		//$strList .= "\t<graph gid='".$cityName."' title='".$cityName."'>\n";
		//if()
		//$strList .= "\t</graph>\n";
    } 

	$strList .="</graphs>\n</chart>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentCityAdmission.xml";
	//UtilityManager::writeXML($strList, $xmlFilePath);
	echo "city";
}
/* END: function to fetch student city data */

/* START: function to fetch student hostel admission data */
if($searchStudent=='Gender'){

	$strList = "";
	$yearRecordArray = $studentAdmissionManager->getDistinctDegreeYear();
	$cnt = count($yearRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "\t<value xid='".$yearRecordArray[$i]['admissionYear']."'>".$yearRecordArray[$i]['admissionYear']."</value>\n";
    } 
	$strList .="</series>\n<graphs>\n";

	$strList .="\n\t<graph gid='Male' title='Male'>";
	$cnt1 = count($yearRecordArray);
	for($j=0;$j<$cnt1;$j++) {

		$condition = "WHERE stu.studentGender='M' AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";
		$totalArray = $studentAdmissionManager->getCountGenderYear($condition);
		 
		$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~M~gender\")'>".$totalArray[0]['totalRecords']."</value>";
	} 

	$strList .="\n\t</graph>"; 

	$strList .="\n\t<graph gid='Female' title='Female'>";
	$cnt1 = count($yearRecordArray);
	for($j=0;$j<$cnt1;$j++) {

		$condition = "WHERE stu.studentGender='F' AND YEAR(dateOfAdmission) = '".$yearRecordArray[$j]['admissionYear']."'";
		$totalArray = $studentAdmissionManager->getCountGenderYear($condition);
		$strList .= "\n\t\t<value xid='".$yearRecordArray[$j]['admissionYear']."' url='javascript:showData(\"".$yearRecordArray[$j]['admissionYear']."~F~gender\")'>".$totalArray[0]['totalRecords']."</value>";
	} 

	$strList .="\n\t</graph>"; 
		 
	$strList .="\n</graphs>\n</chart>";
	 
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentGenderAdmission.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "gender";
}
/* END:  function to fetch student hostel admission data */


//$History: getStudentAdmission.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Management
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-19   Time: 3:30p
//Updated in $/LeapCC/Library/Management
//Updated files with InstituteId values in queries
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/27/08   Time: 5:32p
//Created in $/LeapCC/Library/Management
//intial checkin
?>