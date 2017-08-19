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
define('MODULE','TestWisePerformanceComparisonReport');
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
$rollNos = trim($REQUEST_DATA['studentRollNos']);

if($testIds=='' or $rollNos==''){
    echo 'Invalid input data';
    die;
}

//validating input data
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
$countInterval = count($intervalArr);
$foundArray = $reportManager->getTestMarksComparisonData($testIds);
//to make the max. and avg. lines visble,creating a dummy entry as o
$tempCnt=count($foundArray);
if($tempCnt==1){
    $foundArray[$tempCnt]['maxMarksScored']=0;
    $foundArray[$tempCnt]['avgMarksScored']=0;
    $foundArray[$tempCnt]['testAbbr']='';
    $foundArray[$tempCnt]['testIndex']='';
    $foundArray[$tempCnt]['maxMarks']='';
    $foundArray[$tempCnt]['testId']=-1;
}

$cnt = count($foundArray);

//Now fetch individual marks
$foundArray1 = $reportManager->getTestMarksIndividualData($testIds,$queryConditions);
$cnt1 = count($foundArray1);

$strList='';
if($cnt){
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
        $strList .="<series>\n\t";
        for($k=0;$k<$cnt;$k++){
           if($foundArray[$k]['testId']!=-1){
            $strList .="<value xid='".$k."'>".$foundArray[$k]['testAbbr'].'-'.$foundArray[$k]['testIndex']."</value>\n\t";
           }
           else{
               $strList .="<value xid='".$k."'></value>\n\t";
           }
        }
        $strList .="\n</series><graphs>";

        $strList .="\n\t<graph gid='1' title='Max. Marks' type='line' balloon_text='Max. Marks'>";
        for($i=0;$i<$cnt;$i++) {
           if($foundArray[$i]['testId']!=-1){
            $strList .= "\n\t\t<value xid='".$i."'>".$foundArray[$i]['maxMarksScored']."</value>";
           }
           else{
               $strList .= "\n\t\t<value xid='".$i."'>0</value>";
           }
        }
        $strList .="\n\t</graph>\n";

        $strList .="\n\t<graph gid='2' title='Avg. Marks' type='line' balloon_text='Avg. Marks'>";
        for($i=0;$i<$cnt;$i++) {
            if($foundArray[$i]['testId']!=-1){
             $strList .= "\n\t\t<value xid='".$i."'>".$foundArray[$i]['avgMarksScored']."</value>";
            }
            else{
              $strList .= "\n\t\t<value xid='".$i."'>0</value>";
            }
        }
        $strList .="\n\t</graph>\n";


        $ch=1;
        for($i=0;$i<$len1;$i++) {
          $strList .="\n\t<graph gid='".($i+3)."' type='column' balloon_text=\"".str_replace('"','',trim($studentRollNos[$i]))."\" title=\"".str_replace('"','',trim($studentRollNos[$i]))."\">";
          for($k=0;$k<$cnt;$k++){
             $fl=0;
            for($j=0;$j<$cnt1;$j++) {
               if($foundArray[$k]['testId']==$foundArray1[$j]['testId'] and $foundArray1[$j]['rollNo']==trim($studentRollNos[$i])){
                $strList .= "\n\t\t<value xid='".$k."'>".$foundArray1[$j]['marksScored']."</value>";
                $fl=1;
                break;
               }
            }
            if(!$fl){
                $strList .= "\n\t\t<value xid='".$k."'>0</value>";
            }
          }
          $strList .="\n\t</graph>\n";
        }

        $strList .="\n</graphs>\n</chart>";
}


$xmlFilePath = TEMPLATES_PATH."/Xml/testMarksComparisonData.xml";
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
//$History: ajaxTestMarksComparisonDistribution.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/12/09   Time: 14:32
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified graph type in "Test wise performance comparison" report in
//teacher end
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 29/10/09   Time: 14:46
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Avg. Marks display in "Test marks comparison" report
?>