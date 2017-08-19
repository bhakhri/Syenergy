<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
        return chr(160).$comments; 
     }
}

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
    }
    else{
        $queryConditions .= ' SUM( IF( CEIL(per) BETWEEN '.trim($tRange[0]).'  AND '.trim($tRange[1]).' , 1, 0 ) ) AS "'.trim($tRange[0]).' - '.trim($tRange[1]).'"';
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
$foundArray = $teacherManager->getSubjectWiseDistribution($selectionConditions,$queryConditions,$showGraceMarks,$conductingAuthorityCondition,$groupConditions);
$cnt = count($foundArray);

    $subjectCodes ='';
    $cnt = count($foundArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$foundArray[$i]);
        if($subjectCodes!=''){
          $subjectCodes .=',';
        }
        $subjectCodes .=$foundArray[$i]['subjectCode'];
    }
    
    
    $subjecttype = ",Subject Type,".parseCSVComments($REQUEST_DATA['subjectTypeName']);
    if($REQUEST_DATA[subjectTypeName]=='All') {
       $subjecttype = "";
    }

    $groupname = ",Group,".parseCSVComments($REQUEST_DATA['groupName']);
    if($REQUEST_DATA[groupName]=='All') {
       $groupname = "";
    }
    
    $nn = '\n';  
    if($subjecttype == "" && $groupname == "") {
      $nn = '';  
    }
    
    $exam = "\nExam Type,".parseCSVComments($examTypeString);
    if($examTypeString=='All') {
       $exam = "";
    }

	$csvData = '';
    
    //$csvData .= "Subject Wise Performance Print, $conditionType \n";
    $csvData .= "Time Table, ".parseCSVComments($REQUEST_DATA['timeTableName']);   
    $csvData .= "\n Class, ".parseCSVComments($REQUEST_DATA['className']);   
    $csvData .= "$nn $groupname $subjecttype";
    //$csvData .= "\n Subject, ".parseCSVComments($subjectCodes);   
    //$csvData .= "\n Range, ".parseCSVComments($testRange);  
    $csvData .= "\n Show Grace Marks, ".parseCSVComments($graceMarks)."$exam";   
    $csvData .= "\n";                                        
    
    $csvData .= "#, Subject ";
    $len=count($intervalArr);
    for($i=0;$i<$len;$i++){
        $csvData .=', '.str_replace('-','to',$intervalArr[$i]);
    }
    $csvData .=" \n ";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['subjectCode']);
        for($i=0;$i<$len;$i++){
           $csvData .=', '.parseCSVComments($record[$intervalArr[$i]]);
        }
		$csvData .= "\n";
	}
	
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="subjectWiseMarksDistribution.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: subjectWiseMarksDistributionCSV.php $
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