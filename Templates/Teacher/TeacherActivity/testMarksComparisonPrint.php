<?php 
//This file is used as printing version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    


$testIds = trim($REQUEST_DATA['testIds']);
$rollNos = trim($REQUEST_DATA['studentRollNos']);

//$testIdArr  = explode(',',$testIds);

if($testIds=='' or $rollNos==''){
    echo 'Invalid input data';
    die;
}

$queryConditions='';
$studentRollNos=explode(',',$rollNos);
$len1=count($studentRollNos);

for($i=0;$i<$len1;$i++){
  if($queryConditions!=''){
      $queryConditions .= ',' ;
  }
  $queryConditions .="'".add_slashes(trim($studentRollNos[$i]))."'";
}

$queryConditions =" AND s.rollNo IN ( ".$queryConditions." ) ";
    
//Now fetch marks 
$foundArray = $studentManager->getTestMarksComparisonData($testIds);
$cnt = count($foundArray);

//Now fetch individual marks 
$foundArray1 = $studentManager->getTestMarksIndividualData($testIds,$queryConditions); 
$cnt1 = count($foundArray1);

$valueArray=array();
$testNames='';

if ($cnt) {
  for($l=0;$l<$cnt;$l++){
      $valueArray[$l]['srNo']=($l+1);
      $valueArray[$l]['testNames']=$foundArray[$l]['testAbbr'].'-'.$foundArray[$l]['testIndex']; 
      $valueArray[$l]['maxMarksScored']=$foundArray[$l]['maxMarksScored'];
      $valueArray[$l]['avgMarksScored']=$foundArray[$l]['avgMarksScored']; 
      $testId=$foundArray[$l]['testId'];
      for($i=0;$i<$len1;$i++){
         $fl=0;
         $rollNo=trim($studentRollNos[$i]);
         for($j=0;$j<$cnt1;$j++){
            if($rollNo==$foundArray1[$j]['rollNo'] and $testId==$foundArray1[$j]['testId']){
                $valueArray[$l]['marksScored'.$i]=$foundArray1[$j]['marksScored'];
                $fl=1;
                break;
            } 
         }
         if(!$fl){
           $valueArray[$l]['marksScored'.$i]=0;    
         } 
      }
      if($testNames!=''){
            $testNames .=',';
        }
      $testNames .=$foundArray[$l]['testAbbr'].'-'.$foundArray[$l]['testIndex'];
    }
}

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Test Wise Performance Comparison Report');
    $reportManager->setReportInformation("Conducting Authority : $REQUEST_DATA[cAuthority] Test Type Category : $REQUEST_DATA[categoryName] Subject: $REQUEST_DATA[subjectCode]<br/>  Tests : $testNames <br> Roll Nos : $rollNos");
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%" left="left"', "align='left' ");
	$reportTableHead['testNames']           =   array('Test','width=12% align="left"', 'align="left"');
    $reportTableHead['maxMarksScored']      =   array('Max. Marks','width=15% align="right"', 'align="right"');
    $reportTableHead['avgMarksScored']      =   array('Avg. Marks','width=15% align="right"', 'align="right"');
    for($i=0;$i<$len1;$i++){
     $reportTableHead['marksScored'.$i]      =   array(trim($studentRollNos[$i]),'width=6% align="right"', 'align="right"');
    }
	
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testMarksComparisonPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 29/10/09   Time: 14:46
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added Avg. Marks display in "Test marks comparison" report
?>