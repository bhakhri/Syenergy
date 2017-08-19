<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 17-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupWisePerformanceReport');
define('ACCESS','view');
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	  UtilityManager::ifTeacherNotLoggedIn(true); //for teachers
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$reportManager = TeacherManager::getInstance();

$testTypeCategoryIds=trim($REQUEST_DATA['testTypeCategoryIds']);
$classId=trim($REQUEST_DATA['classId']);
$subjectId=trim($REQUEST_DATA['subjectId']);
$groupIds=trim($REQUEST_DATA['groupIds']);
$groupNames=trim($REQUEST_DATA['groupNames']);
//$conditionType=trim($REQUEST_DATA['conditionType']);
//$conditionRange=trim($REQUEST_DATA['conditionTypeRange']);

//if($testTypeCategoryIds=='' or $classId=='' or $subjectId=='' or $groupIds=='' or $conditionType=='' or $groupNames==''){
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

//$conditionName    = ' ROUND(AVG(tm.marksScored),2) AS avgMarksScored';
$conditionName    = ' ROUND((SUM(tm.marksScored)/SUM(tm.maxMarks))*100,2) AS avgMarksScored';
$fieldName        = 'avgMarksScored';
$queryConditions  = '';
$belowCondition   = '';

$queryConditions .=' AND t.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.testTypeCategoryId IN ('.$testTypeCategoryIds.') AND t.groupId IN ('.$groupIds.')';
//$queryConditions .=' AND t.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.groupId IN ('.$groupIds.')';


//Now fetch marks distribution
$foundArray = $reportManager->getTestMarksGroupWiseComparisonData($conditionName,$queryConditions,$belowCondition);

$cnt = count($foundArray);

/*
    $groupNames=UtilityManager::makeCSList($foundArray,'groupName');
    $groupIdArray=array_unique(explode(',',UtilityManager::makeCSList($foundArray,'groupId')));
    $groupNames=array_unique(explode(',',$groupNames));
    $count=count($groupNames);
*/
$testTypeCategoryIdArray = array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'testTypeCategoryId'))));
$testTypeCategoryNames   = array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'testTypeName'))));
$testTypeCategoryCount   = count($testTypeCategoryIdArray);

$groupIdArray      = array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'groupId'))));
$groupNamesArray   = array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'groupName'))));
$groupCount        = count($groupNamesArray);

/*
$groupArray=explode(',',$groupIds);
$groupCnt=count($groupArray);
$groupNameArray=explode(',',$groupNames);
*/

$strList ='';
    //histogram type chart
    if($cnt){
        $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
        $strList .="<series>\n\t";
        for($k=0;$k<$groupCount;$k++){
            $strList .="<value xid='".$k."'>".$groupNamesArray[$k]."</value>\n\t";
        }
        $strList .="\n</series><graphs>";
        for($i=0;$i<$testTypeCategoryCount;$i++) {
            $strList .="\n\t<graph gid='".$testTypeCategoryNames[$i]."' title='".$testTypeCategoryNames[$i]."'>";
            $fl=0;
            for($j=0;$j<$cnt;$j++) {
               if($foundArray[$j]['testTypeCategoryId']==$testTypeCategoryIdArray[$i]){
                  $classId            = $foundArray[$j]['classId'];
                  $subjectId          = $foundArray[$j]['subjectId'];
                  $groupId            = $foundArray[$j]['groupId'];
                  $testTypeCategoryId = $foundArray[$j]['testTypeCategoryId'];
                 $strList .= "\n\t\t<value xid='".$fl."' url='javascript:showData(".$classId.",".$groupId.",".$subjectId.",".$testTypeCategoryId.");'>".$foundArray[$j][$fieldName]."</value>";
                 $fl++;
               }
            }
           $strList .="\n\t</graph>\n";
        }
        $strList .="\n</graphs>\n</chart>";

        /*FOR GROUP IN X-AXIS AND TEST TYPE CATEGORY IN Y-AXIS*/
        /*
	    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	    $strList .="<series>\n\t";
	    for($k=0;$k<$count;$k++){
		    $strList .="<value xid='".$k."'>".$groupNames[$k]."</value>\n\t";
	    }
	    $strList .="\n</series><graphs>";

        for($i=0;$i<$cnt;$i++) {
		    $strList .="\n\t<graph gid='".$foundArray[$i]['testTypeName']."' title='".$foundArray[$i]['testTypeName']." ( ".$foundArray[$i]['countTests']." )'>";
            $fl=-1;
		    for($j=0;$j<$count;$j++) {
               if($groupIdArray[$j]==$foundArray[$i]['groupId']){
			     $strList .= "\n\t\t<value xid='".$j."' url='javascript:showData(\"".$foundArray[$j]['groupId']."\",\"".$foundArray[$i]['testTypeCategoryId']."\",\"".$foundArray[$i]['subjectId']."\",\"".$foundArray[$i]['classId']."\")'>".$foundArray[$i][$fieldName]."</value>";
                 $fl=$j;
               }
               if($fl!=$j){
                 $strList .= "\n\t\t<value xid='".$j."'>0</value>";
               }
		    }
		    $strList .="\n\t</graph>\n";
	    }
	    $strList .="\n</graphs>\n</chart>";
        */

        /*FOR TEST TYPE CATEGORY IN X-AXIS AND GROUP IN Y-AXIS*/
        /*
        $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
        $strList .="<series>\n\t";
        for($k=0;$k<$testTypeCategoryCount;$k++){
            $strList .="<value xid='".$k."'>".$testTypeCategoryNames[$k]."</value>\n\t";
        }
        $strList .="\n</series><graphs>";
        $strList .="\n\t<graph gid='".$foundArray[0]['groupName']."' title='".$foundArray[0]['groupName']."'>";
        $chk=$foundArray[0]['groupId'];
        $fl=0;
        for($i=0;$i<$cnt;$i++) {
            if($chk!=$foundArray[$i]['groupId']){
              $strList .="\n\t</graph>\n";
              $strList .="\n\t<graph gid='".$foundArray[$i]['groupName']."' title='".$foundArray[$i]['groupName']."'>";
              $chk=$foundArray[$i]['groupId'];
              $fl=0;
            }

            for($j=0;$j<$cnt;$j++) {
               if($foundArray[$i]['groupId']==$foundArray[$j]['groupId'] and $foundArray[$i]['testTypeCategoryId']==$foundArray[$j]['testTypeCategoryId']){
                 //$strList .= "\n\t\t<value xid='".$fl."' url='javascript:showData(\"".$foundArray[$j]['groupId']."\",\"".$foundArray[$i]['testTypeCategoryId']."\",\"".$foundArray[$i]['subjectId']."\",\"".$foundArray[$i]['classId']."\")'>".$foundArray[$j][$fieldName]."</value>";
                 $strList .= "\n\t\t<value xid='".$fl."'>".$foundArray[$j][$fieldName]."</value>";
                 $fl++;
               }
            }
        }
        $strList .="\n</graphs>\n</chart>";
        */
    }

$xmlFilePath = TEMPLATES_PATH."/Xml/groupPerformanceStackData.xml";

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
//$History: ajaxGroupWiseMarksDistribution.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/12/09   Time: 18:50
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Group Wise Performane Report" in teacher login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/11/09    Time: 16:11
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Group Wise Distribution" report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:56
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added files for "Group Wiser Performance Report"
?>