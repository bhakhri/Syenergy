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
    
$timeTableLabelId  = trim($REQUEST_DATA['timeTableId']);
$subjectTypeId     = trim($REQUEST_DATA['subjectType']);
$subjectIds        = trim($REQUEST_DATA['subjectIds']);
$classId           = trim($REQUEST_DATA['classId']);
$groupId           = trim($REQUEST_DATA['groupId']);
$testRange         = trim($REQUEST_DATA['testMarksRange']);
$examType          = trim($REQUEST_DATA['examType']); 


if($timeTableLabelId=='' or $subjectTypeId=='' or $subjectIds=='' or $testRange=='' or $classId=='' or $examType=='' or $groupId==''){
    echo 'Required Paramaters Missing';
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
    
    if(trim($REQUEST_DATA['rangeType'])==2){ //if absoulte comparison is required
     $queryConditions .= ' SUM( IF( CEIL(marksScored) BETWEEN '.trim($tRange[0]).'  AND '.trim($tRange[1]).' , 1, 0 ) ) AS "'.trim($tRange[0]).' - '.trim($tRange[1]).'"';
     $conditionType='';
    }
    else{
        $queryConditions .= ' SUM( IF( CEIL(per) BETWEEN '.trim($tRange[0]).'  AND '.trim($tRange[1]).' , 1, 0 ) ) AS "'.trim($tRange[0]).' - '.trim($tRange[1]).'"';
        $conditionType=' ( in percentage )';
    }
    $intervalArr[]=trim($tRange[0]).' - '.trim($tRange[1]);
}

$selectionConditions= ' AND ttm.classId='.$classId.' AND ttc.timeTableLabelId='.$timeTableLabelId.' AND ttm.subjectId IN ('.$subjectIds.')';

$groupConditions =' AND sg.classId='.$classId.' AND sb.subjectId IN ('.$subjectIds.')';
if($groupId!='-1'){
   $groupConditions .=' AND sg.groupId='.$groupId; 
}

if($subjectTypeId!=0){
    $selectionConditions .='AND sub.subjectTypeId='.$subjectTypeId;
}

//whether to include grace marks
if(trim($REQUEST_DATA['showGraceMarks'])==1){
    $showGraceMarks=1;
    $graceMarks='Yes';
}
else{
    $showGraceMarks=0;
    $graceMarks='No';
}

if($examType==1){
   $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (1,2,3)';
   $examTypeString='All';
}
else if($examType==2){
   $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (1,3)';
   $examTypeString='Inetrnal';   
}
else{
   $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (2)';
   $examTypeString='External';   
}

    
//Now fetch marks distribution
$countInterval = count($intervalArr);
$foundArray = $studentManager->getSubjectWiseDistribution($selectionConditions,$queryConditions,$showGraceMarks,$conductingAuthorityCondition,$groupConditions);
$cnt = count($foundArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $TD=0;
	$valueArray = array();
    $subjectCodes='';
    
    $reportTableHead                =  array();
    $reportTableHead['srNo']        =  array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['subjectCode'] =  array('Subject','width=12% align="left"', 'align="left"');
    $len=count($intervalArr);
    for($j=0;$j<$len;$j++){
        $reportTableHead[$intervalArr[$j]]    =   array(str_replace('-','to', $intervalArr[$j]),'width="8%" align="right" ', 'align="right"');
    }
    
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$foundArray[$i]);
        if($subjectCodes!=''){
            $subjectCodes .=',';
        }
        $subjectCodes .=$foundArray[$i]['subjectCode'];
    }
    
    $subjecttype = "<b>Subject Type :</b> $REQUEST_DATA[subjectTypeName]";
    if($REQUEST_DATA[subjectTypeName]=='All') {
       $subjecttype = "";
    }
    
    $groupname = "<b>Group :</b> $REQUEST_DATA[groupName]";
    if($REQUEST_DATA[groupName]=='All') {
       $groupname = "";
    }
    
    $exam = "<b>Exam Type :</b> ".$examTypeString;
    if($examTypeString=='All') {
       $exam = "";
    }
  
    $reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Subject Wise Performance Print'.$conditionType);
    $reportManager->setReportInformation("<b>Time Table :</b> $REQUEST_DATA[timeTableName]   
                                             <b>Class :</b> $REQUEST_DATA[className] <br/>$groupname $subjecttype <br/>
                                             <b>Subject :</b> ".$subjectCodes."<br/><b>Range :</b> $testRange<br/>
                                             <b>Show Grace Marks :</b> $graceMarks $exam");

	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: subjectWiseMarksComparisonPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/12/09    Time: 11:08
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Subject Wise Performance Graph"---Added the option of
//include/exclude grace marks in this report
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:15
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//created "Subject Wise Performance" report
?>