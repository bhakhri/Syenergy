<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 11-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/DashBoardManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$dashboardManager = DashBoardManager::getInstance();

$classId = $REQUEST_DATA['classId'];

if($classId != '') {
	$activitySubjectRecordArray = $dashboardManager->getDistinctSubject(" AND stc.classId  = $classId");
	 
	$cnt = count($activitySubjectRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$activitySubjectRecordArray[$i]['subjectId']."'>".$activitySubjectRecordArray[$i]['subjectCode']."</value>\n";
		$subjectArr[$activitySubjectRecordArray[$i]['subjectId']]=$activitySubjectRecordArray[$i]['subjectCode'];
	}
	$strList .="</series>\n";

	$strList .="<graphs>\n";

	$activityRecordArray = $dashboardManager->getDistinctTestType();
	$i=0;
    $cnt1 = count($activityRecordArray);
	for($i=0;$i<$cnt1;$i++) {

		$strList .="\t<graph gid='".$activityRecordArray[$i]['testTypeCategoryId']."' title='".$activityRecordArray[$i]['testTypeName']."'>\n";
		foreach($subjectArr as $key=>$val){
		
			$testCountArray = $dashboardManager->getCountTests($key,$activityRecordArray[$i]['testTypeCategoryId'],$classId);
			//print_r($testCountArray);
			$cnt = count($testCountArray);
			$strList .= "\t\t<value xid='".$key."' url='javascript:showDetailData(\"".$key."\",\"".$activityRecordArray[$i]['testTypeCategoryId']."\",\"".$classId."\")'>".$cnt."</value>\n";
			
		}
		$strList .="\t</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";
    

	$xmlFilePath = TEMPLATES_PATH."/Xml/testTypeStackData.xml";
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

//$History: getTestGraph.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-09-07   Time: 1:37p
//Updated in $/LeapCC/Library/Index
//Updated with session check
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/22/09    Time: 2:58p
//Updated in $/LeapCC/Library/Index
//Updated test type distribution to have unique value for class
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/19/09    Time: 5:56p
//Updated in $/LeapCC/Library/Index
//Updated Admin dashboard with role permission, test type and average
//attendance
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:04p
//Created in $/LeapCC/Library/Index
//Intial checkin
?>