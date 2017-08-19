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
	define('MODULE','scUserRole');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ScStudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    /////////////////////////
    
    if(trim($REQUEST_DATA['userId'] ) != '') {
		$userId = $REQUEST_DATA['userId'];
    
    //$totalArray = $studentReportsManager->getTotalUserData($roleId);

	$loginThreeMonthsRecordArray = $studentReportsManager->getUserThreeMonthsLoginData($userId);
	$loginSixMonthsRecordArray = $studentReportsManager->getUserSixMonthsLoginData($userId);
	$loginNineMonthsRecordArray = $studentReportsManager->getUserNineMonthsLoginData($userId);
	$loginOneYearRecordArray = $studentReportsManager->getUserOneYearLoginData($userId);


	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

$strList .="<series>\n";
	
	$strList .= "<value xid='0'>3 months</value>\n";
	$strList .= "<value xid='1'>6 months</value>\n";
	$strList .= "<value xid='2'>9 months</value>\n";
	$strList .= "<value xid='3'>1 year</value>\n";

$strList .="</series>\n";
$strList .="<graphs>\n";
 
	$strList .="<graph gid='1'>\n";
		$strList .= "<value xid='0' bullet='round'>".$loginThreeMonthsRecordArray[0]['totalRecords']."</value>\n";
		$strList .= "<value xid='1' bullet='round'>".$loginSixMonthsRecordArray[0]['totalSixRecords']."</value>\n";
		$strList .= "<value xid='2' bullet='round'>".$loginNineMonthsRecordArray[0]['totalNineRecords']."</value>\n";
		$strList .= "<value xid='3' bullet='round'>".$loginOneYearRecordArray[0]['totalYearRecords']."</value>\n";
	$strList .="</graph>\n";

	$strList .="<graph gid='2'>\n";
		$strList .= "<value xid='0' bullet='round'>".$loginThreeMonthsRecordArray[0]['totalRecords']."</value>\n";
		$strList .= "<value xid='1' bullet='round'>".$loginSixMonthsRecordArray[0]['totalSixRecords']."</value>\n";
		$strList .= "<value xid='2' bullet='round'>".$loginNineMonthsRecordArray[0]['totalNineRecords']."</value>\n";
		$strList .= "<value xid='3' bullet='round'>".$loginOneYearRecordArray[0]['totalYearRecords']."</value>\n";
	$strList .="</graph>\n";
}
$strList .="</graphs>\n";
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
    
// $History: populateUserLogin.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/28/09    Time: 4:41p
//Created in $/LeapCC/Library/StudentReports
//copy from sc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/10/09    Time: 6:46p
//Created in $/Leap/Source/Library/ScStudentReports
//new ajax files to show the graph
//
//

?>