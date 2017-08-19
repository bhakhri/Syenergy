<?php
//----------------------------------------------------------------------------------------------------
//This file creates a query for the "StudentListsReport" and generates an array of the selected fields 
//
// Author :Ajinder Singh
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------

   
	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();
	 
	$arr=explode('-',$REQUEST_DATA['degree']);
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['studyPeriodId']."' AND batchId='".$REQUEST_DATA['batchId']."' ";   
	$classIdArray= $reportManager->getClassId($classFilter);

	$reportRecordArray = $reportManager->getStudentLabelData($arr[0], $arr[1], $arr[2]);

	//////// excel //////////   
	 if($REQUEST_DATA['act'] =='excel') {
		//$Records = implode(',',$reportRecordArray[0]);
		foreach($reportRecordArray[0] as $records => $value){	
			$Records.="$records,";
		}
		$countRows = count($reportRecordArray);
		for($i=0;$i<$countRows;$i++) {
			$Records .="\r\n".implode(',',$reportRecordArray[$i]);
		}
	 }	
	///////////////////////
	/// generate query string ///
	foreach($REQUEST_DATA AS $key => $value){
		if(trim($querystring=='')){
			$querystring="$key=$value";
		}
		else{
			$querystring.="&$key=$value";
		}
	}	 
	 
	 	
//$History: initListStudentLabelsReports.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:20p
//Updated in $/Leap/Source/Library/StudentReports
//improved page designing.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:46a
//Created in $/Leap/Source/Library/StudentReports
//file added for : creating studentLabels report
//
//*****************  Version 1  *****************
//User: Ajinder       Date: 7/15/08    Time: 12:44p
//Created in $/Leap/Source/Library/StudentReports
//added new file for StudentLabelsReports
?>
