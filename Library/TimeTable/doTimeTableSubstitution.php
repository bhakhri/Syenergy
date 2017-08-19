<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To substitute time table record of a teacher by another teacher
// Author : Dipanjan Bbhattacharjee
// Created on : (10.10.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    define('MODULE','SwapTeacherTimeTable');
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
    $replaceEmpId=trim($REQUEST_DATA['replaceTeacherId']);
    $replacingEmpId=trim($REQUEST_DATA['replacingTeacherId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
    $records=$REQUEST_DATA['hEle'];
    
    if($timeTableLabelId==''){
        echo SELECT_TIME_TABLE;
        die;
    }
    if($replaceEmpId==''){
        echo SELECT_TEACHER_TO_SUBSTITUTE;
        die;
    }
    if($replacingEmpId==''){
        echo SELECT_TEACHER_BY_SUBSTITUTE;
        die;
    }
    if($replaceEmpId==$replacingEmpId){
        echo SAME_EMPLOYEE_RESTRICTION;
        die;
    }
    if($fromDate=='' or $toDate==''){
        echo 'Date fields missing';
        die;
    }
    if($records==''){
        echo 'Invalid time table records';
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
    
    $diff=$server_date - $from_date; //from date cannot be small than current date
    if($diff>0){
        echo TIME_TABLE_FROM_DATE_VALIDATION;
        die;
    }
    
    $diff=$server_date - $to_date;  //to date cannot be small than current date
    if($diff>0){
        echo TIME_TABLE_TO_DATE_VALIDATION;
        die;
    }
    
    $diff=$to_date - $from_date;   //from date cannot be small than to date
    if($diff<0){
        echo TIME_TABLE_DATE_VALIDATION;
        die;
    }
    
    //print_r($records);
    $cnt=count($records);
    for($i=0;$i<$cnt;$i++){
        $rec=explode('~',$records[$i]);
        if(count($rec)!=3){
            echo 'Invalid Records';
            die;
        }
        $timeTableId=$rec[0];
        $empId=$rec[1];
        $repId=$rec[2];
        
        if($empId==$repId){
           echo SAME_EMPLOYEE_RESTRICTION;
           die;
        }
        
        //fetch records corresponding to  $timeTableIdfrom time_table

        $timeTableRecordArray=$timeTableManager->getTimeTableRecords($timeTableId);
        $count1=count($timeTableRecordArray);
        if($count1==0){
            echo 'Invalid time table records';
            die;
        }
        
        //CHECK THE CONFLICTS FROM GENERAL FUNCTION
        
        //$checkArray = array('server','adjustment');

		$postPeriodSlotId = $timeTableRecordArray[0]['periodSlotId'];
		$periodsArray = $timeTableManager->getAllPeriods($postPeriodSlotId);
		$periodNumberArray = array();
		foreach($periodsArray as $periodRecord) {
			$periodNumberArray[$periodRecord['periodId']] = $periodRecord['periodNumber'];
		}
		$timeTableConflictManager->setProcess('show conflicts');
        $conflict = $timeTableConflictManager->checkConflict($repId, $timeTableRecordArray[0]['subjectId'], $timeTableRecordArray[0]['groupId'], $timeTableRecordArray[0]['roomId'], $timeTableRecordArray[0]['daysOfWeek'], $periodNumberArray[$timeTableRecordArray[0]['periodId']], $postPeriodSlotId, $timeTableRecordArray[0]['timeTableLabelId'], $fromDate, $toDate, $timeTableId, 'swap');

        if($conflict != NO_CONFLICTS_FOUND) {
            //$conflict = $timeTableConflictManager->showError();
			echo $conflict.'~'.$timeTableId;
            die;
        }
    }

  //Calculating days of week between from and to dates
   $sDate1=explode('-',$fromDate);
   $sDate3=explode('-',$toDate);
   $from_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
   $to_date=gregoriantojd($sDate3[1], $sDate3[2], $sDate3[0]);
   $diff=$to_date - $from_date; 
   $daysOfWeekString='';
    for($i=0;$i<=$diff;$i++){
        $calDate  = date('w',mktime(0, 0, 0, $sDate1[1]  , $sDate1[2]+$i, $sDate1[0]));
        if($calDate==0){
            $calDate=7; //as we consider sunday as 7
        }
        if($daysOfWeekString!=''){
            $daysOfWeekString.=',';
        }
        $daysOfWeekString .=$calDate;
    }
    if($diff==0){
        $daysOfWeekString=date('w',mktime(0, 0, 0, $sDate1[1]  , $sDate1[2], $sDate1[0]));;
    }
    $daysOfWeekArray= array_unique(explode(',',$daysOfWeekString));
    
    
  //now build the insert query
  $isActive=1;
  $adjustmentType=3; //for swap/substitution
  
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
      $insStr='';
      for($i=0;$i<$cnt;$i++){
        $rec=explode('~',$records[$i]);
        $timeTableId=$rec[0];
        $empId=$rec[1];
        $repId=$rec[2];
        
        //fetch records corresponding to  $timeTableIdfrom time_table
        //this query is redundant as we are constructing multiple insertion query
        //outside the loop
        $timeTableRecordArray=$timeTableManager->getTimeTableRecords($timeTableId);
        //days of week mismatched
        if(!in_array($timeTableRecordArray[0]['daysOfWeek'],$daysOfWeekArray)){
            echo 'Time table days mismatching';
            die;
        }
        if($insStr!=''){
            $insStr .=' , ';
        }
        $insStr .=' ( '.$timeTableId.' , '.$timeTableRecordArray[0]['roomId'].' , '.$repId.' , '.$timeTableRecordArray[0]['groupId'].' , '.$timeTableRecordArray[0]['instituteId'].' , '.$timeTableRecordArray[0]['sessionId'].' , '.$timeTableRecordArray[0]['daysOfWeek'].' , '.$timeTableRecordArray[0]['periodSlotId'].' , '.$timeTableRecordArray[0]['periodId'].' , '.$timeTableRecordArray[0]['subjectId'].' , "'.$fromDate.'" , "'.$toDate.'" , '.$isActive.' , '.$timeTableLabelId.' , '.$userId.' , '.$empId.',"'.date('Y-m-d').'",Null,Null,'.$adjustmentType.' ) ';
      }
    if($insStr==''){
       echo FAILURE;
       die;        
    }
    
    //now add the values in database
    $return=$timeTableManager->addAdjustedTimeTableRecords($insStr);
    if($return===false){
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
// $History: doTimeTableSubstitution.php $
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:35
//Updated in $/LeapCC/Library/TimeTable
//Revert back the code as instructed by ajinder sir.
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 12/11/09   Time: 11:59
//Updated in $/LeapCC/Library/TimeTable
//Corrected code as error format of conflict manager changed
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 5/11/09    Time: 12:13
//Updated in $/LeapCC/Library/TimeTable
//Modified Swap/Substitution module as one new field "adjustmentType" is
//added in "time_table_adjustment" table
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 2/11/09    Time: 18:10
//Updated in $/LeapCC/Library/TimeTable
//Done bug fixing.
//Bug ids---
//00001919,00001920,00001921,00001923,00001924
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 10/26/09   Time: 5:03p
//Updated in $/LeapCC/Library/TimeTable
//fixed issue with clashing adjustment.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 22/10/09   Time: 15:31
//Updated in $/LeapCC/Library/TimeTable
//Corrected insert query
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 10/22/09   Time: 11:52a
//Updated in $/LeapCC/Library/TimeTable
//removed unwanted code.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 21/10/09   Time: 12:57
//Updated in $/LeapCC/Library/TimeTable
//Checked in for the time being
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/21/09   Time: 12:08p
//Updated in $/LeapCC/Library/TimeTable
//added code for checking conflicts.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/10/09   Time: 13:13
//Updated in $/LeapCC/Library/TimeTable
//Temporarily checked in
?>