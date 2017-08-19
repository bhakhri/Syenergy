<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To move/copy time table record
// Author : Jaineesh
// Created on : (29.10.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    define('MODULE','MoveTeacherTimeTable');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

	require_once(MODEL_PATH . "/TimeTableConflictManager.inc.php");
    $timeTableConflictManager = TimeTableConflictManager::getInstance();
    
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $userId=$sessionHandler->getSessionVariable('UserId');
    $date=date('Y-m-d');
    
    $timeTableLabelId=trim($REQUEST_DATA['labelId']);
	$typeId=trim($REQUEST_DATA['typeId']);
    $replaceEmpId=trim($REQUEST_DATA['replaceTeacherId']);
    $replacingEmpId=trim($REQUEST_DATA['replacingTeacherId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
	$typeId=trim($REQUEST_DATA['typeId']);
    $records=$REQUEST_DATA['chb'];
	//echo($typeId);

	$typeName = $adjustmentTypeArr[$typeId];
	$typeName = strtolower($typeName);


	if ($fromDate == "") {
		messageBox("<?php echo DATE_FROM_NOT_EMPTY; ?>");
		document.getElementById('fromDate').focus();
		return false;
   }

   if ($toDate == "") {
		messageBox("<?php echo DATE_TO_NOT_EMPTY; ?>");
		document.getElementById('toDate').focus();
		return false;
   }
	
	$gToDate = explode('-',$toDate);
	$gtYear = $gToDate[0];
	$gtMonth = $gToDate[1];
	$gtDate = $gToDate[2];

	$getToDay = date("w", mktime(0, 0, 0, $gtMonth, $gtDate, $gtYear));

	if ($getToDay == 0) {
		$getToDay = 7;//as we consider sunday as 7
	}

	//$getToDayName = $daysArr[$getToDay];

	
	if($timeTableLabelId==''){
        echo SELECT_TIME_TABLE;
        die;
    }

	if($typeId == ''){
        echo SELECT_ADJUSTMENT_TYPE;
        die;
    }

	if($fromDate=='' or $toDate==''){
        echo 'Date fields missing';
        die;
    }

	$serverDate=explode('-',date('Y-m-d'));
    $sDate0=$serverDate[0].$serverDate[1].$serverDate[2];
    
    $sDate1=explode('-',$fromDate);
    $sDate2=$sDate1[0].$sDate1[1].$sDate1[2];
    
    $sDate3=explode('-',$toDate);
    $sDate4=$sDate3[0].$sDate3[1].$sDate3[2];
    
    $server_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
        
    $from_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
    $to_date=gregoriantojd($sDate3[1], $sDate3[2], $sDate3[0]);
    
    $diff=$to_date - $from_date;   //from date cannot be small than to date
    if($diff<0){
        echo TIME_TABLE_DATE_VALIDATION;
        die;
    }

	foreach($records as $timetableId){
		$querySeprator = '';
		if($insertValue!=''){
			$querySeprator = ",";
		}
		$insertValue .= "$querySeprator('".$timetableId."')";
	}
	
	
	$getTimeTableArray = $timeTableManager->getTimeTableDetail($insertValue);
	$count = count($getTimeTableArray);

	
if ($count > 0 && is_array($getTimeTableArray)) {
	for ($i=0;$i<$count;$i++) {

		$postPeriodSlotId = $getTimeTableArray[$i]['periodSlotId'];
		$periodsArray = $timeTableManager->getAllPeriods($postPeriodSlotId);
		$periodNumberArray = array();
		foreach($periodsArray as $periodRecord) {
			$periodNumberArray[$periodRecord['periodId']] = $periodRecord['periodNumber'];
		}

		$timeTableConflictManager->setProcess('show conflicts');
		$conflict = $timeTableConflictManager->checkConflict($getTimeTableArray[$i]['employeeId'], $getTimeTableArray[$i]['subjectId'], $getTimeTableArray[$i]['groupId'], $getTimeTableArray[$i]['roomId'], $getToDay, $periodNumberArray[$getTimeTableArray[$i]['periodId']], $postPeriodSlotId, $getTimeTableArray[$i]['timeTableLabelId'], $fromDate, $toDate, $records[$i], $typeName);
		

		if($conflict != NO_CONFLICTS_FOUND) {
			echo $conflict.'~'.$records[$i];
            die;
        }

		if($insStr!=''){
            $insStr .=' , ';
        }
        $insStr .=' ( '.$records[$i].' , '.$getTimeTableArray[$i]['roomId'].' , '.$getTimeTableArray[$i]['employeeId'].' , '.$getTimeTableArray[$i]['groupId'].' , '.$getTimeTableArray[$i]['instituteId'].' , '.$getTimeTableArray[$i]['sessionId'].' , '.$getToDay.' , '.$getTimeTableArray[$i]['periodSlotId'].' , '.$getTimeTableArray[$i]['periodId'].' , '.$getTimeTableArray[$i]['subjectId'].' , "'.$toDate.'" , "'.$toDate.'" , '.$getTimeTableArray[$i]['timeTableLabelId'].' ,'.$getTimeTableArray[$i]['employeeId'].','.$userId.',"'.date('Y-m-d').'",1,Null,Null,'.$typeId.') ';
	}
}
else {
	echo FAILURE;
	die;
}

	if($insStr==''){
       echo FAILURE;
       die;        
    }
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		
		$insertMoveCopyTimeTable = $timeTableManager->insertCopyMoveTimeTable($insStr);

		if($insertMoveCopyTimeTable === false){
			echo FAILURE;
		    die;  			
		}
    
    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo SUCCESS;
        die;
    }
   else {
      echo FAILURE;
      die;
    }
	}
 else {
  echo FAILURE;
  die;
 }

// for VSS
// $History: doTimeTableCopyMove.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/12/09   Time: 5:19p
//Updated in $/LeapCC/Library/TimeTable
//corrected coding.
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/04/09   Time: 4:28p
//Updated in $/LeapCC/Library/TimeTable
//give link move/copy teacher time table and add new field adjustment
//type in time_table_adjustment table
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/03/09   Time: 1:22p
//Updated in $/LeapCC/Library/TimeTable
//released for Jaineesh for time being.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/02/09   Time: 11:41a
//Updated in $/LeapCC/Library/TimeTable
//send 7 for sunday as days of week
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:32a
//Created in $/LeapCC/Library/TimeTable
//new file for move/copy time table
//
?>