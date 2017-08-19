<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Rajeev Aggarwal
// Created on : 13-Aug-2008
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
	$studentReportsManager = StudentReportsManager::getInstance();

	$timeTable	   = $REQUEST_DATA['timeTable'];
	$degree		   = $REQUEST_DATA['degree'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	$subjectId     = $REQUEST_DATA['subjectId']; 
	$groupId       = $REQUEST_DATA['groupId']; 
	$testCategoryId= $REQUEST_DATA['testCategoryId']; 

	$typeName      = $REQUEST_DATA['typeName']; 
	$groupName     = $REQUEST_DATA['groupName']; 
	$categoryName  = $REQUEST_DATA['categoryName']; 
	 
	$condition = ' AND  sub.subjectTypeId ='.$subjectTypeId.' AND
				 ttc.timeTableLabelId='.$timeTable.' AND 
				 ttc1.testTypeCategoryId='.$testCategoryId.' AND 
				 tt.classId ='.$degree;

	if($groupId){
	
		$condition .= ' AND tt.groupId='.$groupId;
	}
	if($subjectId){
	
		$condition .= ' AND tt.subjectId='.$subjectId;
	} 
	$foundArray = $studentReportsManager->getCountTestTypeGroup($condition);
	//echo "<pre>";
	//print_r($foundArray);
	$cnt = count($foundArray);
	//$strList ='';
	if($cnt){

		for($k=0;$k<$cnt;$k++){
			
			$subArr[] = $foundArray[$k]['subjectCode'];
			$groupArr[] = $foundArray[$k]['groupShort'];
			$subgroupArr[$foundArray[$k]['subjectCode'].'~'.$foundArray[$k]['groupShort']] = $foundArray[$k]['totalRecords'];
			$subIdArr[] = $foundArray[$k]['subjectId'];
			$groupIdArr[] = $foundArray[$k]['groupId'];
		} 
		
		$subArrUnique = array_unique($subArr);
		$subIdArrUnique = array_unique($subIdArr);
		$grpArrUnique = array_unique($groupArr);
		$grpIdArrUnique = array_unique($groupIdArr);
		//echo "<pre>";
		foreach($subIdArrUnique as $sub1){
		
			$subIdArrUnique1[]=	$sub1;
		}
		//print_r($subIdArrUnique1);
		
  
		$strList ='';
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n\t";
		$k=0;
		foreach($subArrUnique as $subject){
		
			$strList .="<value xid='".$k."'>".$subject."</value>\n\t";
			$k++;
		} 
		$strList .="\n</series>\n<graphs>";

		$m=0;
		foreach($grpArrUnique as $group){
			
			$groupId = $grpIdArrUnique[$m];
			$k=0;
			$strList .="\n\t<graph gid='".$group."' title='".$group."'>";
			foreach($subArrUnique as $subject){

				$strList .= "\n\t\t<value xid='".$k."' url='javascript:showData(\"".$subIdArrUnique1[$k]."\",\"".$degree."\",\"".$groupId."\",\"".$testCategoryId."\",\"".$group."\")'>".$subgroupArr[$subject.'~'.$group]."</value>";
				$k++;
			} 
			$strList .="\n\t</graph>\n";
			$m++;
		
		} 
		$strList .="\n</graphs>\n</chart>";
	}else{
	
		$strList ='';
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n</chart>";
	} 
	$xmlFilePath = TEMPLATES_PATH."/Xml/testTypeConsolidatedData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open user activity data xml file");
	}
	echo SUCCESS;

// $History: initTestTypeConsolidatedReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-12-24   Time: 6:11p
//Updated in $/LeapCC/Library/StudentReports
//fixed 0002177,0002212
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/15/09    Time: 3:55p
//Updated in $/LeapCC/Library/StudentReports
//Updated test type distribution report according to all subjects and all
//groups
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/08/09    Time: 5:51p
//Created in $/LeapCC/Library/StudentReports
//Intail checkin: Added test type distribution 
?>