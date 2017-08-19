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
    
    $testIds    = trim($REQUEST_DATA['testId']);
    $testRange = trim($REQUEST_DATA['interval']);

    if($testIds=='' or $testRange==''){
     echo 'Invalid input data';
     die;
    }

//validating input data  
$tR=explode(',',$testRange);
$len1=count($tR);

for($i=0;$i<$len1;$i++){
    $tRange=explode('-',$tR[$i]);
    $len2=count($tRange);
    if($len2!=2){
        echo INVALID_MARKS_RANGE;
        die;
    }
    for($k=0;$k<$len2;$k++){
        if(!is_numeric(trim($tRange[$k]))){
           echo ENTER_NUMERIC_VALUE_FOR_MARKS_RANGE;
           die;
        }
    }
}
	
    //$studentRecordArray = $studentManager->getTestMarksDistributionDetailData($testIds,' AND tm.marksScored BETWEEN '.$tRange[0].' AND '.$tRange[1]);
    $studentRecordArray = $studentManager->getTestMarksDistributionDetailData($testIds,' HAVING per BETWEEN '.$tRange[0].' AND '.$tRange[1]);
    $cnt = count($studentRecordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $TD=0;
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$studentRecordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Test Wise Performance Detail Print');
    $reportManager->setReportInformation("Conducting Authority: $REQUEST_DATA[cAuthority] Test Type Category: $REQUEST_DATA[categoryName] Subject: $REQUEST_DATA[subjectCode] <br/>Test : ".$studentRecordArray[0][testAbbr]."-".$studentRecordArray[0][testIndex]."  Range :$testRange");

	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%"', "align='center' ");
    $reportTableHead['studentName']         =   array('Name','width=12% align="left"', 'align="left"');
	$reportTableHead['rollNo']			    =	array('Roll No.','width=10% align="left"', 'align="left"');
	$reportTableHead['universityRollNo']    =   array('Univ. Roll No.','width=10% align="left"', 'align="left"');
	$reportTableHead['className']		    =	array('Class','width="10%" align="left" ', 'align="left"');
    $reportTableHead['marksScored']         =   array('Marks Scored','width="10%" align="right" ', 'align="right"');
    $reportTableHead['maxMarks']            =   array('Max. Marks','width="10%" align="right" ', 'align="right"');


	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testMarksDistributionDetailContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/11/09    Time: 17:31
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Test Wise Distribution report" : Now distribution will be
//calculated based upon percentage of marks scored and not on actual
//marks scored
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 27/10/09   Time: 15:27
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Added files for "Test Wise Performance Report" module
?>