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
define('MODULE','SubjectWisePerformanceReport');
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
$foundArray = $reportManager->getTestMarksDistributionNew($testIds,$queryConditions);
$cnt = count($foundArray);
$strList ='';
if(trim($REQUEST_DATA['chartTypeId'])==1){
    //histogram type chart
    if($cnt){

	    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	    $strList .="<series>\n\t";
	    for($k=0;$k<$countInterval;$k++){
		    $strList .="<value xid='".$k."'>".$intervalArr[$k]."</value>\n\t";
	    }
	    $strList .="\n</series><graphs>";

	    for($i=0;$i<$cnt;$i++) {
		$ttStr = $foundArray[$i]['testAbbr'].'-'.$foundArray[$i]['testIndex'].' ('.$foundArray[$i]['groupShort'].')';
		    $strList .="\n\t<graph gid='".$ttStr."' title='".$ttStr."'>";
		    for($j=0;$j<$countInterval;$j++) {
			    $strList .= "\n\t\t<value xid='".$j."' url='javascript:showData(\"".$intervalArr[$j]."\",\"".$foundArray[$i]['testId']."\")'>".$foundArray[$i][$intervalArr[$j]]."</value>";
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
            $strList .="<value xid='".$intervalArr[$k]."'>".$intervalArr[$k]."</value>\n\t";
        }
        $strList .="\n</series><graphs>";
        for($i=0;$i<$cnt;$i++) {
	   $ttStr = $foundArray[$i]['testAbbr'].'-'.$foundArray[$i]['testIndex'].' ('.$foundArray[$i]['groupShort'].')';
            $strList .="\n\t<graph gid='".$ttStr."' title='".$ttStr."'>";
            for($j=0;$j<$countInterval;$j++) {
                $strList .= "\n\t\t<value xid='".$intervalArr[$j]."' url='javascript:showData(\"".$intervalArr[$j]."\",\"".$foundArray[$i]['testId']."\")'>".$foundArray[$i][$intervalArr[$j]]."</value>";
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
            $strList .="<value xid='".$intervalArr[$k]."'>".$intervalArr[$k]."</value>\n\t";
        }
        $strList .="\n</series><graphs>";

        for($i=0;$i<$cnt;$i++) {
	   $ttStr = $foundArray[$i]['testAbbr'].'-'.$foundArray[$i]['testIndex'].' ('.$foundArray[$i]['groupShort'].')';
            $strList .="\n\t<graph gid='".$ttStr."' title='".$ttStr."'>";
            for($j=0;$j<$countInterval;$j++) {
                $strList .= "\n\t\t<value xid='".$intervalArr[$j]."' url='javascript:showData(\"".$intervalArr[$j]."\",\"".$foundArray[$i]['testId']."\")'>".$foundArray[$i][$intervalArr[$j]]."</value>";
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

$xmlFilePath = TEMPLATES_PATH."/Xml/subjectPerformanceStackData.xml";
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
//$History: ajaxTestMarksDistribution.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 16/11/09   Time: 14:53
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Bug Fixing of "Test Marks Distribution" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/11/09    Time: 17:31
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Test Wise Distribution report" : Now distribution will be
//calculated based upon percentage of marks scored and not on actual
//marks scored
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 27/10/09   Time: 15:26
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added files for "Test Wise Performance Report" module
?>
