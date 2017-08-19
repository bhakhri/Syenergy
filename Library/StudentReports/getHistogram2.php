<?php
//This file sends the data, creates the image on runtime
//
// Author :Ajinder Singh
// Created on : 21-Oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
$subjectId = $REQUEST_DATA['subjectId'];

$subArray = $studentManager->getSingleField('subject', 'subjectCode', " WHERE subjectId  = $subjectId");
$subName = $subArray[0]['subjectCode'];

$xmlData = '';
$xmlData .="<?xml version='1.0' encoding='UTF-8'?>\n";
$chartStart = "\n<chart>";
$chartEnd = "\n</chart>";
$seriesStart = "\n<series>";
$seriesEnd = "\n</series>";
$graphsStart= "\n<graphs>";
$graphsStart1 = "\n<graph gid='1'>";
$graphsStart2 = "\n<graph gid='2'>";
$graphEnd = "\n</graph>";
$graphsEnd = "\n</graphs>";

$seriesData = '';
$graphData  = '';

$XRangeArray = Array();
$degreeId = $REQUEST_DATA['degreeId'];
$gradingFormula = $REQUEST_DATA['gradingFormula'];

$histogramRangeArray = $studentManager->getLineChartData($subjectId, $degreeId, $gradingFormula);

$subjectMaxMarksArray = $studentManager->getSubjectMaxMarks($subjectId, $degreeId);
$subjectMaxMarks = $subjectMaxMarksArray[0]['maxMarks'];

$startMarks = 0;
$endMarks = $subjectMaxMarks;
$marksArray = array();
while ($startMarks <= $endMarks) {
	$marksArray[$startMarks] = 0;
	$startMarks++;
	
}


foreach($histogramRangeArray as $histogramRecord) {
	$studentCount = $histogramRecord['cnt2'];
	$marksScored = $histogramRecord['cnt'];
	$marksArray[$marksScored] = $studentCount;
}

$marksArray2 = Array();
$nonZeroFound = false;
foreach($marksArray as $marksScored => $studentCount) {
	if ($studentCount == 0) {
		if ($nonZeroFound === false) {
			continue; 
		}
	}
	else {
		$nonZeroFound = true;
	}
	$marksArray2[$marksScored] = $studentCount;
}

$marksArray = array_reverse($marksArray2, true);
$marksArray2 = Array();
$nonZeroFound = false;
foreach($marksArray as $marksScored => $studentCount) {
	if ($studentCount == 0) {
		if ($nonZeroFound === false) {
			continue; 
		}
	}
	else {
		$nonZeroFound = true;
	}
	$marksArray2[$marksScored] = $studentCount;
}


$marksArray = array_reverse($marksArray2, true);

foreach($marksArray as $marksScored => $studentCount) {
	if ($studentCount > 0) {
		$minValue = $marksScored;
		break;
	}
}

foreach($marksArray as $marksScored => $studentCount) {
	$seriesData .= "\n<value xid=\"$marksScored\">$marksScored</value>";
	$graphData .= "\n<value xid=\"$marksScored\" >$studentCount</value>";
	$graphData2 .= "\n<value xid=\"$marksScored\" >$studentCount</value>";

}


/*
$i = 0;
foreach($histogramRangeArray as $histogramRecord) {
	$studentCount = $histogramRecord['cnt2'];
	$marksScored = $histogramRecord['cnt'];
	$seriesData .= "\n<value xid=\"$i\">$marksScored</value>";
	$graphData .= "\n<value xid=\"$i\" >$studentCount</value>";
	$graphData2 .= "\n<value xid=\"$i\" >$studentCount</value>";
	$i++;
}
*/
$file = TEMPLATES_PATH.'/Xml/courseMaksTransferred.xml';


$xmlData .= $chartStart . $seriesStart . $seriesData . $seriesEnd . $graphsStart . $graphsStart1 . $graphData . $graphEnd . $graphsStart2 . $graphData2 . $graphEnd . $chartEnd;
//echo $xmlData;

require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::writeXML($xmlData, $file);
echo SUCCESS;


//$History: scGetHistogram2.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/30/09    Time: 6:30p
//Updated in $/Leap/Source/Library/ScStudentReports
//added code for trimming values with 0 students from both ends.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/04/09    Time: 1:59p
//Updated in $/Leap/Source/Library/ScStudentReports
//added code to make histogram to show all marks even if no student has
//scored that marks.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/20/09    Time: 5:14p
//Created in $/Leap/Source/Library/ScStudentReports
//file added for grading-advanced
//



?>