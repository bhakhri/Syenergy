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
define('MODULE','COMMON');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$reportManager = StudentReportsManager::getInstance();

$classId = $REQUEST_DATA['classId'];
$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
$subjectId = $REQUEST_DATA['subjectId'];
$groupId = $REQUEST_DATA['groupId'];

$condition = ' AND ttm.classId ='.$classId.' AND s.subjectTypeId ='.$subjectTypeId;
if($subjectId)
	$condition .=' AND s.subjectId ='.$subjectId;
if($groupId)
	$condition .=' AND sg.groupId ='.$groupId;

/* theory */
if($subjectTypeId==1){
	
	$intervalArr = array("0-9","10-13","14-16","17-24","25-30","31-35","36-40");
	$countInterval = count($intervalArr);
	$foundArray = $reportManager->getSubjectInternalMarksTheory($condition);
	$cnt = count($foundArray);
}

/* practical */
if($subjectTypeId==2){
	
	$intervalArr = array("0-9","10-12","13-15","16-18","19-21","22-24","25-27","28-30");
	$countInterval = count($intervalArr);
	$foundArray = $reportManager->getSubjectInternalMarksPractical($condition);
	$cnt = count($foundArray);
}
$strList ='';
if($cnt){

	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n\t";
	for($k=0;$k<$countInterval;$k++){
	
		$strList .="<value xid='".$k."'>".$intervalArr[$k]."</value>\n\t";
	} 
	$strList .="\n</series><graphs>";

	for($i=0;$i<$cnt;$i++) {
		
		$strList .="\n\t<graph gid='".$foundArray[$i]['subjectCode']."' title='".$foundArray[$i]['subjectCode']."'>";
		for($j=0;$j<$countInterval;$j++) {

			$strList .= "\n\t\t<value xid='".$j."' url='javascript:showData(\"".$intervalArr[$j]."\",\"".$foundArray[$i]['subjectId']."\",\"".$classId."\",\"".$subjectTypeId."\")'>".$foundArray[$i]['total'.$j]."</value>";
		}
		$strList .="\n\t</graph>\n";
	} 
	 
	$strList .="\n</graphs>\n</chart>";
} 
$xmlFilePath = TEMPLATES_PATH."/Xml/transferredStackData.xml";
if(is_writable($xmlFilePath)){

	$handle = @fopen($xmlFilePath, 'w');
	if (@fwrite($handle, $strList) === FALSE){
		die("unable to write");
	}
}
else{
	logError("unable to open user activity data xml file");
}
 
if($cnt){

	echo SUCCESS;
}
else{

	echo FAILURE;
}
//$History: scGetInternalMarksGraph.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:54p
//Updated in $/LeapCC/Library/StudentReports
//Fixed bug no 1441
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/11/09    Time: 3:14p
//Updated in $/LeapCC/Library/StudentReports
//Updated as per theory and practical marks distribution right now these
//are made hardcoded
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:02p
//Updated in $/LeapCC/Library/StudentReports
//Updated formatting
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/22/09    Time: 10:24a
//Created in $/LeapCC/Library/StudentReports
//intial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/17/09    Time: 7:14p
//Updated in $/Leap/Source/Library/ScIndex
//Updated with test type report on dashboard
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 11/19/08   Time: 10:59a
//Created in $/Leap/Source/Library/ScIndex
//intial checkin
?>