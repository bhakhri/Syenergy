<?php 
//This file is used as csv version for final marks
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
    
	//used to parse csv data
	function parseCSVComments($comments) {
		 $comments = str_replace('"', '""', $comments);
		 $comments = str_ireplace('<br/>', "\n", $comments);
		 if(eregi(",", $comments) or eregi("\n", $comments)) {
		   return '"'.$comments.'"'; 
		 } 
		 else {
		 return $comments; 
		 }
	}

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
    }
}

	$csvData = '';
	//*******building the top row*********
    $csvData .= "#, Test, Max. Marks, Avg. Marks";
    for($i=0;$i<$len1;$i++){
     $csvData .= ", ".parseCSVComments(trim($studentRollNos[$i]));
    }
    $csvData .= "\n";
    
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',  '.parseCSVComments($record['testNames']).',  '.parseCSVComments($record['maxMarksScored']).',  '.parseCSVComments($record['avgMarksScored']);
        for($i=0;$i<$len1;$i++){
          $csvData .= ', '.parseCSVComments($record['marksScored'.$i]);
        }
		$csvData .= "\n";
	}
	
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="testMarksComparison.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: testMarksComparisonCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 29/10/09   Time: 14:46
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added Avg. Marks display in "Test marks comparison" report
?>