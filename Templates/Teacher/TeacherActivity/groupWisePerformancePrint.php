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
    


$testTypeCategoryIds=trim($REQUEST_DATA['testTypeCategoryIds']);
$classId=trim($REQUEST_DATA['classId']);
$subjectId=trim($REQUEST_DATA['subjectId']);
$groupIds=trim($REQUEST_DATA['groupIds']);
/*
$conditionType=trim($REQUEST_DATA['conditionType']);
$conditionRange=trim($REQUEST_DATA['conditionTypeRange']);
*/
//if($testTypeCategoryIds=='' or $classId=='' or $subjectId=='' or $groupIds=='' or $conditionType==''){
if($testTypeCategoryIds=='' or $classId=='' or $subjectId=='' or $groupIds==''){
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


	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Group Wise Performance Report');
    $reportManager->setReportInformation("Conducting Authority : $REQUEST_DATA[cAuthority] Class: $REQUEST_DATA[className]  Subject: $REQUEST_DATA[subjectCode] <br/>Test Type Category : ".implode(',',$testTypeCategoryNames)."   <br/>Group : ".implode(',',$groupNames));
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%" align="left"', "align='left' ");
	$reportTableHead['testTypeName']        =   array('Test Type Category','width=12% align="left"', 'align="left"');
    
    for($l=0;$l<$count;$l++){
     $reportTableHead[$groupNames[$l]]       =   array($groupNames[$l],'width=10% align="right"', 'align="right"');
     //echo $groupNames[$l].'<br>';
    }
    
    //echo '<pre>';
    //print_r($reportTableHead);
    //print_r($foundArray);
    //print_r($valueArray);   
    //die;
    
            
	
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: groupWisePerformancePrint.php $
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