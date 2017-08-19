<?php 
//This file is used as printing version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    


$classId = trim($REQUEST_DATA['classId']);
$groupId = trim($REQUEST_DATA['groupId']);
$subjectId = trim($REQUEST_DATA['subjectId']);
$testTypeCategoryId = trim($REQUEST_DATA['testTypeCategoryId']);
$conditionType=trim($REQUEST_DATA['conditionValue']);
$conditionRange=trim($REQUEST_DATA['conditionRange']);

if($groupId=='' or $testTypeCategoryId=='' or $subjectId=='' or $classId=='') {
    echo 'Invalid input data';
    die;
}

/*
if($conditionType==1){
    $conditionString =' Maximum Marks';
}
else if($conditionType==2){
    $conditionString =' Average Marks';
}
else if($conditionType==3){
    $conditionString =' Marks greathe than or euqal to '.$conditionRange;
}
else if($conditionType==4){
    $conditionString =' Marks less than '.$conditionRange;
}
else{
    echo 'Invalid input data';
    die;
}
*/

//Fetch Group information
$groupArray=$studentManager->getGroupInformation(' WHERE g.groupId='.$groupId);
$groupName=trim($groupArray[0]['groupName'])!='' ? trim($groupArray[0]['groupName']) : NOT_APPLICABLE_STRING;

//Fetch Test Type Category information
$testTypeCategoryArray=$studentManager->getTestTypeCategoryInformation(' WHERE t.testTypeCategoryId='.$testTypeCategoryId);
$testTypeCategoryName=trim($testTypeCategoryArray[0]['testTypeName'])!='' ? trim($testTypeCategoryArray[0]['testTypeName']) : NOT_APPLICABLE_STRING;
    
//Now fetch marks information 
$foundArray = $studentManager->getGroupWiseTestMarksDetails($groupId,$testTypeCategoryId,$subjectId,$classId);
$cnt = count($foundArray);
$totalTests=count(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'testId'))));



$valueArray=array();
for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$foundArray[$i]);
}

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Group Wise Performance Detail Report');
    //$reportManager->setReportInformation("Conducting Authority : $REQUEST_DATA[cAuthority] Test Type Category : $testTypeCategoryName Subject: $REQUEST_DATA[subjectCode]  Group : $groupName <br> Condition : $conditionString  Total tests conducted : $totalTests");
    $reportManager->setReportInformation("Conducting Authority : $REQUEST_DATA[cAuthority] Test Type Category : $testTypeCategoryName Subject: $REQUEST_DATA[subjectCode]  Group : $groupName <br> Total tests conducted : $totalTests");
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%" left="left"', "align='left' ");
	$reportTableHead['studentName']         =   array('Name','width=12% align="left"', 'align="left"');
    $reportTableHead['rollNo']              =   array('Roll No.','width=10% align="left"', 'align="left"');
    $reportTableHead['universityRollNo']    =   array('Univ. Roll No.','width=12% align="left"', 'align="left"');
    $reportTableHead['testName']            =   array('Test','width=10% align="left"', 'align="left"');
    $reportTableHead['maxMarks']            =   array('Max. Mark','width=10% align="right"', 'align="right"');
    $reportTableHead['marksScored']         =   array('Marks Scored','width=10% align="right"', 'align="right"');
    
	
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: groupWisePerformanceDetailPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/12/09   Time: 18:50
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Group Wise Performane Report" in teacher login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:57
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Added files for "Group Wiser Performance Report" 
?>