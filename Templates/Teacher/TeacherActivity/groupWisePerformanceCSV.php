<?php 
//This file is used as csv version for display attendance
//
// Author :Rajeev Aggarwal
// Created on : 08-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

    
    
	//used to parse csv data
	function parseCSVComments($comments) {
		$comments = str_replace('"', '""', $comments);
		$comments = str_ireplace('<br/>', "\n", $comments);
		if(eregi(",", $comments) or eregi("\n", $comments)) {
		
			return '"'.$comments.'"'; 
		} 
		else{
			
			return $comments; 
		}
	}

$testTypeCategoryIds=trim($REQUEST_DATA['testTypeCategoryIds']);
$classId=trim($REQUEST_DATA['classId']);
$subjectId=trim($REQUEST_DATA['subjectId']);
$groupIds=trim($REQUEST_DATA['groupIds']);
$conditionType=trim($REQUEST_DATA['conditionType']);
$conditionRange=trim($REQUEST_DATA['conditionTypeRange']);

if($testTypeCategoryIds=='' or $classId=='' or $subjectId=='' or $groupIds=='' ){
    echo 'Invalid input data';
    die;
}

//validating input data
//$conditionType >2 then check for empty and non-numeric values
/*
if($conditionType>2){
    if($conditionRange==''){
        echo EMPTY_CONDITION_RANGE;
        die;
    }
    if(!is_numeric($conditionRange)){
        echo INVALID_CONDITION_RANGE;
        die;
    }
}

if($conditionType==1){
    $conditionName=' MAX(tm.marksScored) AS maxMarksScored';
    $fieldName='maxMarksScored';
    $queryConditions='';
}
else if($conditionType==2){
    $conditionName=' ROUND(AVG(tm.marksScored),2) AS avgMarksScored';
    $fieldName='avgMarksScored';
    $queryConditions='';
}
else if($conditionType==3){
    $conditionName=' tm.marksScored';
    //$fieldName='marksScored';
    $fieldName='per';
    $belowCondition=' HAVING per >='. $conditionRange;
}
else if($conditionType==4){
    $conditionName=' tm.marksScored';
    //$fieldName='marksScored';
    $fieldName='per';
    $belowCondition=' HAVING per <'. $conditionRange;
}
else{
    echo 'Invalid input data';
    die;
}

*/

$conditionName    = ' ROUND((SUM(tm.marksScored)/SUM(tm.maxMarks))*100,2) AS avgMarksScored';
$fieldName        = 'avgMarksScored';
$queryConditions  = '';
$belowCondition   = '';

$queryConditions .=' AND t.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.testTypeCategoryId IN ('.$testTypeCategoryIds.') AND t.groupId IN ('.$groupIds.')';
//$queryConditions .=' AND t.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.groupId IN ('.$groupIds.')';

    
//Now fetch marks distribution
$foundArray = $studentManager->getTestMarksGroupWiseComparisonData($conditionName,$queryConditions,$belowCondition);
$cnt = count($foundArray);

$testTypeCategoryIdArray = array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'testTypeCategoryId'))));
$testTypeCategoryNames   = array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'testTypeName'))));
$testTypeCategoryCount   = count($testTypeCategoryIdArray);

$groupNames=UtilityManager::makeCSList($foundArray,'groupName');
$groupIdArray=array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'groupId'))));
$groupNames=array_values(array_unique(explode(',',$groupNames)));
$count=count($groupNames);

/*FOR GROUP IN X-AXIS AND TEST TYPE CATEGORY IN Y-AXIS*/ 
/*
$valueArray=array();
$testTypeCategoryNames='';
$groupNames='';
$groupArray=array();
if ($cnt) {
  for($l=0;$l<$cnt;$l++){
      $groupId=$foundArray[$l]['groupId'];
      if($testTypeCategoryNames!=''){
            $testTypeCategoryNames .=',';
        }
      $testTypeCategoryNames .=$foundArray[$l]['testTypeName'];
      
      if(!in_array($groupId,$groupArray)){
          $groupArray[]=$groupId;
          if($groupNames!=''){
            $groupNames .=',';
          }
         $groupNames .=$foundArray[$l]['groupName'];
      }
      else{
          continue;
      }
      $valueArray[$l]['srNo']=($l+1);
      $valueArray[$l]['groupName']=$foundArray[$l]['groupName'];
      
         for($j=0;$j<$cnt;$j++){
            if($groupId==$foundArray[$j]['groupId']){
                $valueArray[$l][$fieldName.$j]=$foundArray[$j][$fieldName];
            }
            else{
                $valueArray[$l][$fieldName.$j]=0;
            }
         }
      
    }
}
 
	$csvData = '';
	$csvData .= "#, Group ";
    for($l=0;$l<$cnt;$l++){
        $csvData .=", ".$foundArray[$l]['testTypeName'];
    }
    $csvData .="\n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',  '.parseCSVComments($record['groupName']);
        for($l=0;$l<$cnt;$l++){
           $csvData .=", ".$record[$fieldName.$l];
        }    
		$csvData .= "\n";
	}
*/

/*FOR TEST TYPE CATEGORY IN X-AXIS AND GROUP IN Y-AXIS*/
$valueArray=array();
if($cnt){
    for($i=0;$i<$testTypeCategoryCount;$i++){
        $valueArray[$i]['srNo']=($i+1);
        $valueArray[$i]['testTypeName']=$testTypeCategoryNames[$i];
        $testTypeCategoryId=$testTypeCategoryIdArray[$i];
        $groupArray=array();
        for($j=0;$j<$cnt;$j++){
            if($testTypeCategoryId==$foundArray[$j]['testTypeCategoryId']){
                $valueArray[$i][$foundArray[$j]['groupName']]=$foundArray[$j][$fieldName];
                $groupArray[]=$foundArray[$j]['groupName'];
            }
        }
        for($j=0;$j<$cnt;$j++){
            if(!in_array($foundArray[$j]['groupName'],$groupArray)){
                $valueArray[$i][$foundArray[$j]['groupName']]=0;
            }
        }
    }
}

    $csvData = '';
    $csvData .= "#, Test Type Category ";
    for($l=0;$l<$count;$l++){
        $csvData .=", ".$groupNames[$l];
    }
    $csvData .="\n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].',  '.parseCSVComments($record['testTypeName']);
        for($l=0;$l<$count;$l++){
           $csvData .=", ".$record[$groupNames[$l]];
        }    
        $csvData .= "\n";
    }


	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="groupWisePerformanceList.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: groupWisePerformanceCSV.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/12/09   Time: 18:50
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Group Wise Performane Report" in teacher login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/11/09    Time: 16:11
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Group Wise Distribution" report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:57
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Added files for "Group Wiser Performance Report" 
?>