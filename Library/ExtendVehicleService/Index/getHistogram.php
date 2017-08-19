<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
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

$getClassId = $REQUEST_DATA['classId'];
$str = "<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
$series = "<series>\n";
$graphs = "<graphs>\n";
$graph1 = "<graph gid='1'>\n";
$graph2 = "<graph gid='2'>\n";
$valueStr = "";
if($getClassId != "" AND $getClassId != '0') {
	$i = 0;
	$teacherSubjectsArray = $dashboardManager->getClassSubjects($getClassId);
	foreach ($teacherSubjectsArray as $record) {
		$subjectCode = $record['subjectCode'];
		$subjectId = $record['subjectId'];
		$series .= "<value xid='$i'>$subjectCode</value>\n";
		$activityRecordArray = $dashboardManager->getAttendanceThresholdRecords2($subjectId, $getClassId);
		$studentCountSubject = count($activityRecordArray);

		$valueStr .= "<value xid='$i' title='$studentCountSubject'>$studentCountSubject</value>\n<value xid='$i' bullet='round' url='javascript:showStudentAttendanceThresholdData($getClassId,$subjectId)'>$studentCountSubject</value>\n";

		$i++;
	}
	$series .= "</series>";
	$text = $str . $series . $graphs . $graph1 . $valueStr . "</graph>\n" . $graph2 . $valueStr . "</graph>\n" . "</graphs>\n</chart>\n";
	$strList = $text;
	$xmlFilePath = TEMPLATES_PATH."/Xml/averageAttencanceActivityBarData.xml";
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
else {
	$series = "</series>";
	$graphs = "</graphs>";

}

//$History:  $
//
//
?>