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
    
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $userId=$sessionHandler->getSessionVariable('UserId');
    $date=date('Y-m-d');
    $records=trim($REQUEST_DATA['checkedStr']);
    $specialRecords=trim($REQUEST_DATA['altStr']);
    $override=trim($REQUEST_DATA['override']);
    
    if($records==''){
        echo 'Invalid time table records';
        die;
    }
    
  //now build the update query
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
     /*LOGIC
     * 1:If attendance is not taken place for a records then make its isActive=0;
     * 2:If attendance is taken:
     *  2.1: If override is ON then delete attendance and make this record's isActive=0;
     *  2.2: Else,this reord is not updated
     */
     $recordsArray=explode(',',$records);
     if($specialRecords!=''){
      $specialRecordsArray=explode(',',$specialRecords);
     }
     if(count($specialRecordsArray)>0){
         if($override==1){
           $cnt=count($specialRecordsArray);
           for($i=0;$i<$cnt;$i++){
             $timeTableAdjustmentId=$specialRecordsArray[$i];
             //now fetch adjustment entries
             $adjustedArray=$timeTableManager->fetchAdjustedTimeTableData($timeTableAdjustmentId);
             if(count($adjustedArray)>0){
                 //now fetch attendance Ids
                 $attendanceArray=$timeTableManager->fetchAttendanceHistory($adjustedArray[0]['employeeId'],$adjustedArray[0]['classId'],$adjustedArray[0]['groupId'],$adjustedArray[0]['subjectId'],$adjustedArray[0]['fromDate'],$adjustedArray[0]['toDate']);
                 if(count($attendanceArray)>0){
                  $attendanceIds=UtilityManager::makeCSList($attendanceArray,'attendanceId');
                   //now quarantine attendance records
                    $ret=$timeTableManager->quarantineAttendance($attendanceIds);
                    if($ret===false){
                        echo FAILURE;
                        die;
                    }
                   
                   //now delete attendance records
                    $ret=$timeTableManager->deleteAttendance($attendanceIds);
                    if($ret===false){
                        echo FAILURE;
                        die;
                    } 
                 }
             }
             else{
                 echo 'Invalid Arguments';
                 die;
             }
           }
         }
         else{
             echo 'Invalid Arguments';
             die;
         }
     }
     
     //now update adjustment table
     $ret=$timeTableManager->deleteAdjustedTimeTableRecords(' timeTableAdjustmentId IN ('.$records.')');
     if($ret===false){
        echo FAILURE;
        die; 
     }
     
    //now update the values in database
    /*
    $return=$timeTableManager->updateAdjustedTimeTableRecordsForFutureDates($records,$userId);
    if($return===false){
      echo FAILURE;
      die;  
    }
    
    $return=$timeTableManager->updateAdjustedTimeTableRecordsForIntermediateDates($records,$userId);
    if($return===false){
      echo FAILURE;
      die;  
    }
    */
    
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
// $History: doTimeTableAdjustmentCancellation.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/11/09   Time: 10:16
//Updated in $/LeapCC/Library/TimeTable
//Corrected logic of deleting adjustment time table entries 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/10/09   Time: 13:19
//Created in $/LeapCC/Library/TimeTable
//Added code "time table adjustment cancellation"
?>