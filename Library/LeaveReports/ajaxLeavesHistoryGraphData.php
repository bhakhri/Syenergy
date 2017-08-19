<?php
//This file sends the data, creates the image on runtime
// Author :Dipanjan Bhattacharjee
// Created on : 17-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeavesHistoryReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/LeaveReportsManager.inc.php");
$leaveManager = LeaveReportsManager::getInstance();

$leaveSessionId=trim($REQUEST_DATA['leaveSessionId']);
$employeeId=trim($REQUEST_DATA['employeeId']);


if(leaveSessionId=='' or $employeeId==''){
    echo 'Required Paramaters Missing';
    die;
}

$filter ='';

$filter .=' AND l.employeeId='.$employeeId;
if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
  $filter .=' AND l.leaveSessionId='.trim($REQUEST_DATA['leaveSessionId']);
}

	
    
    
//Now fetch marks distribution
$foundArray = $leaveManager->getLeavesHistoryGraphData($filter);

$cnt = count($foundArray);
$strList ='';
if($cnt){
	$leaveArray= array_values(array_unique(explode(',',UtilityManager::makeCSList($foundArray,'leaveStatus'))));
	$uniqueCount=count($leaveArray);

	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n\t";
	for($k=0;$k<$uniqueCount;$k++){
		$strList .="<value xid='".$k."'>".$leaveStatusArray[intval($leaveArray[$k])] ."</value>\n\t";
	} 
	$strList .="\n</series><graphs>";
	$newArray = array();
	foreach($foundArray as $record) {
		$leaveStatus = $record['leaveStatus'];
		$leaveTypeId = $record['leaveTypeId'];
		$leaveTypeName = $record['leaveTypeName'];
		$totalDays = $record['totalDays'];
		$newArray[$leaveTypeName][] = $totalDays;
	}
   foreach ($newArray as $leaveType => $daysArray) {
    $strList .="\n\t<graph gid='$leaveType' title='$leaveType'>";
    $i = 0;
    foreach ($daysArray as $days) {
        $strList .="\n\t\t<value xid='$i' >$days</value>";
        $i++;
    }
    $strList .="\n\t</graph>";
  }
  $strList .="\n</graphs>\n</chart>"; 
}
 
$xmlFilePath = TEMPLATES_PATH."/Xml/leavesHistoryStackData.xml";
 
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
?>