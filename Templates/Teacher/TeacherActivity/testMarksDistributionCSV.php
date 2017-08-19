<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    

//to parse csv values    
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
$foundArray = $teacherManager->getTestMarksDistribution($testIds,$queryConditions);
$cnt = count($foundArray);

    $cnt = count($foundArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$foundArray[$i]);
    }

	$csvData = '';
    $csvData .= "#, Test ";
    $len=count($intervalArr);
    for($i=0;$i<$len;$i++){
        $csvData .=', '.$intervalArr[$i];
    }
    $csvData .=" \n ";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['testName']);
        for($i=0;$i<$len;$i++){
           $csvData .=', '.parseCSVComments($record[$intervalArr[$i]]);
        }
		$csvData .= "\n";
	}
	
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="testMarksDistribution.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: testMarksDistributionCSV.php $
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