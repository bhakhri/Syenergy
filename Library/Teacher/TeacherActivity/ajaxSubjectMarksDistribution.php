<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 17-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWisePerformanceComparisonReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
    
  UtilityManager::ifNotLoggedIn(true);
}
else if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}

UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$reportManager = TeacherManager::getInstance();

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
}
else{
    $showGraceMarks=0;
}


if($examType==1){
   $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (1,2,3)';
}
else if($examType==2){
   $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (1,3)'; 
}
else{
   $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (2)';
}
	
//Now fetch marks distribution
$countInterval = count($intervalArr);
$foundArray = $reportManager->getSubjectWiseDistribution($selectionConditions,$queryConditions,$showGraceMarks,$conductingAuthorityCondition,$groupConditions);
$cnt = count($foundArray);

$strList ='';
if(trim($REQUEST_DATA['chartTypeId'])==1){
    //histogram type chart
    if($cnt){

	    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	    $strList .="<series>\n\t";
	    for($k=0;$k<$countInterval;$k++){
		    $strList .="<value xid='".$k."'>".str_replace('-','to', $intervalArr[$k])."</value>\n\t";
	    } 
	    $strList .="\n</series><graphs>";

	    for($i=0;$i<$cnt;$i++) {
		    $strList .="\n\t<graph gid='".$foundArray[$i]['subjectCode']."' title='".$foundArray[$i]['subjectCode']."'>";
		    for($j=0;$j<$countInterval;$j++) {
			    $strList .= "\n\t\t<value xid='".$j."' url='javascript:showData(\"".$intervalArr[$j]."\",".$foundArray[$i]['classId'].",".$foundArray[$i]['subjectId'].")'>".$foundArray[$i][$intervalArr[$j]]."</value>";
		    }
		    $strList .="\n\t</graph>\n";
	    } 
	    $strList .="\n</graphs>\n</chart>";
    }
}
else if(trim($REQUEST_DATA['chartTypeId'])==2){
    //3D stacked column chart
    if($cnt){
        $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
        $strList .="<series>\n\t";
        for($k=0;$k<$countInterval;$k++){
            $strList .="<value xid='".$intervalArr[$k]."'>".str_replace('-','to', $intervalArr[$k])."</value>\n\t";
        } 
        $strList .="\n</series><graphs>";

        for($i=0;$i<$cnt;$i++) {
            $strList .="\n\t<graph gid='".$foundArray[$i]['subjectCode']."' title='".$foundArray[$i]['subjectCode']."'>";
            for($j=0;$j<$countInterval;$j++) {
                $strList .= "\n\t\t<value xid='".$intervalArr[$j]."' url='javascript:showData(\"".$intervalArr[$j]."\",".$foundArray[$i]['classId'].",".$foundArray[$i]['subjectId'].")'>".$foundArray[$i][$intervalArr[$j]]."</value>";
            }
            $strList .="\n\t</graph>\n";
        } 
        $strList .="\n</graphs>\n</chart>";
    }
}
else if(trim($REQUEST_DATA['chartTypeId'])==3){
    //3D stacked row chart
    if($cnt){
        $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
        $strList .="<series>\n\t";
        for($k=0;$k<$countInterval;$k++){
            $strList .="<value xid='".$intervalArr[$k]."'>".str_replace('-','to', $intervalArr[$k])."</value>\n\t";
        } 
        $strList .="\n</series><graphs>";

        for($i=0;$i<$cnt;$i++) {
            $strList .="\n\t<graph gid='".$foundArray[$i]['subjectCode']."' title='".$foundArray[$i]['subjectCode']."'>";
            for($j=0;$j<$countInterval;$j++) {
                $strList .= "\n\t\t<value xid='".$intervalArr[$j]."' url='javascript:showData(\"".$intervalArr[$j]."\",".$foundArray[$i]['classId'].",".$foundArray[$i]['subjectId'].")'>".$foundArray[$i][$intervalArr[$j]]."</value>";
            }
            $strList .="\n\t</graph>\n";
        } 
        $strList .="\n</graphs>\n</chart>";
    }
}   
else{
    echo INVALID_CHART_TYPE;
    die;
}
 
$xmlFilePath = TEMPLATES_PATH."/Xml/subjectWisePerformanceStackData.xml";
 
if(is_writable($xmlFilePath)){

	$handle = @fopen($xmlFilePath, 'w');
	if (@fwrite($handle, $strList) === FALSE){
		die("unable to write");
	}
}
else{
	logError("unable to open user activity data xml file");
    die;
}
 
if($cnt){
	echo SUCCESS;
    die;
}
else{
	echo FAILURE;
    die;
}
//$History: ajaxSubjectMarksDistribution.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/12/09    Time: 11:08
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Subject Wise Performance Graph"---Added the option of
//include/exclude grace marks in this report
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:10
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//created "Subject Wise Performance" report
?>