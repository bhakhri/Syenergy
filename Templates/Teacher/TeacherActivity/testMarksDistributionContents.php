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
    
$testIds    = trim($REQUEST_DATA['testIds']);
$testRange = trim($REQUEST_DATA['testMarksRange']);

if($testIds=='' or $testRange==''){
    echo 'Invalid input data';
    die;
}

//validating input data
$queryConditions='';
$tR=explode(',',$testRange);
$len1=count($tR);
$intervalArr=array();
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
    if($queryConditions!=''){
        $queryConditions .=' , ';
    }
    //build the query conditions simultaneously
    //$queryConditions .= ' SUM( IF( CEIL(marksScored) BETWEEN '.$tRange[0].'  AND '.$tRange[1].' , 1, 0 ) ) AS "'.$tRange[0].' - '.$tRange[1].'"';
    $queryConditions .= ' SUM( IF( CEIL(per) BETWEEN '.trim($tRange[0]).'  AND '.trim($tRange[1]).' , 1, 0 ) ) AS "'.trim($tRange[0]).' - '.trim($tRange[1]).'"';
    $intervalArr[]=trim($tRange[0]).' - '.trim($tRange[1]);
}

    
//Now fetch marks distribution
$countInterval = count($intervalArr);
$foundArray = $studentManager->getTestMarksDistribution($testIds,$queryConditions);
$cnt = count($foundArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $TD=0;
	$valueArray = array();
    $testNames='';
    
    $reportTableHead                =  array();
    $reportTableHead['srNo']        =  array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['testName']    =  array('Test','width=12% align="left"', 'align="left"');
    $len=count($intervalArr);
    for($j=0;$j<$len;$j++){
        $reportTableHead[$intervalArr[$j]]    =   array("$intervalArr[$j]",'width="6%" align="right" ', 'align="right"');
    }
    
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$foundArray[$i]);
        if($testNames!=''){
            $testNames .=',';
        }
        $testNames .=$foundArray[$i]['testAbbr'].'-'.$foundArray[$i]['testIndex'];
    }

  
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Test wise performance report');
    $reportManager->setReportInformation("Conducting Authority: $REQUEST_DATA[cAuthority] Test Type Category: $REQUEST_DATA[categoryName] Subject: $REQUEST_DATA[subjectCode] <br/>Test : ".$testNames."<br/>  Range :$testRange");

	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testMarksDistributionContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 14/12/09   Time: 12:52
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0002259,0002258,0002257,0002256,0002255,0002252,0002251,
//0002250,0002254
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 16/11/09   Time: 14:53
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Bug Fixing of "Test Marks Distribution" module 
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