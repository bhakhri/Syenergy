<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student attendance in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (16.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    define('MODULE','CopyAttendance');
    define('ACCESS','edit');

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
     UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();
    
    if(trim($REQUEST_DATA['classId'])=='' or trim($REQUEST_DATA['forDate'])=='' or trim($REQUEST_DATA['groupId'])=='' or trim($REQUEST_DATA['periodId'])=='' or trim($REQUEST_DATA['subjectId'])=='' or trim($REQUEST_DATA['targetPeriodId'])==''){
        echo 'Required parameter missing';
        die;
    }
    
    /*CHECK FOR FROZEN CLASS*/
     $classId=trim($REQUEST_DATA['classId']);
     $isFrozenArray=CommonQueryManager::getInstance()->checkFrozenClass($classId);
     if($isFrozenArray[0]['isFrozen']==1){
         echo FROZEN_CLASS_RESTRICTION.$isFrozenArray[0]['className'];
         die;
     }
    /*CHECK FOR FROZEN CLASS*/
    
    
    $serverDate=explode('-',date('Y-m-d'));
    $sDate0=$serverDate[0].$serverDate[1].$serverDate[2];
    $sDate1=explode('-',$REQUEST_DATA['forDate']);
    $sDate2=$sDate1[0].$sDate1[1].$sDate1[2];
    if(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))!='' and trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT')) !="0"){
        if(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))==-1 && trim($sessionHandler->getSessionVariable('RoleId'))==2)
        {
            echo "You can not take attendance because attendance has been freezed by the Admin.";
            die;  
        }
        elseif(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))>0 && trim($sessionHandler->getSessionVariable('RoleId'))==2) {
            $threshold=trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'));
            $start_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
            $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
            $diff=$end_date - $start_date;
                if($diff>$threshold){
                    echo "You can not take attendance older than ".$threshold." days";
                    die;
                }
        }
    }

    
    
    /*********USED TO CHECK DUPLICATE RECORDS**********/
    $duplicateRecordArray=$teacherManager->checkWithAttendance($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['targetPeriodId'],$REQUEST_DATA['forDate']);
    if($duplicateRecordArray[0]['cnt']!=0){
        die("Attendance records are not copied\nas attendance already taken for target period");
    }
    /*
    $duplicateRecordArray=$teacherManager->checkWithDutyLeave($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['targetPeriodId'],$REQUEST_DATA['forDate']);
    if($duplicateRecordArray[0]['cnt']!=0){
        die("Attendance records are not copied\nas duty leaves are approved for target period");
    }
    */
    /*********USED TO CHECK DUPLICATE RECORDS**********/
    
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    

    //****************************************************************************************************************    
    //***********************************************STRAT TRANSCATION************************************************
    //****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {  
        $ret=$teacherManager->copyDailyAttance($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['periodId'],$REQUEST_DATA['forDate'],$REQUEST_DATA['targetPeriodId']);
        if($ret===false){
          echo FAILURE;  
          die;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo SUCCESS;
            die;
        }
        else {
            echo FAILURE;
        }
    }
    else {
      echo FAILURE;
    }


    if(SystemDatabaseManager::getInstance()->startTransaction()) {  
        // Delete the old duty leave (copy duty leave)
        $condition = " classId = '".$REQUEST_DATA['classId']."' AND 
                       groupId = '".$REQUEST_DATA['groupId']."' AND 
                       subjectId = '".$REQUEST_DATA['subjectId']."' AND 
                       dutyDate = '".$REQUEST_DATA['forDate']."' AND 
                       periodId = '".$REQUEST_DATA['targetPeriodId']."' AND
                       instituteId = '".$instituteId."' AND
                       sessionId = '".$sessionId."' ";
        $ret=$teacherManager->deleteCopyDutyLeave($condition);
        if($ret===false){
          echo FAILURE;  
          die;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
           echo SUCCESS;
           die;
        }
        else {
           echo FAILURE;
        }
    }
    else {
      echo FAILURE;
    }

    if(SystemDatabaseManager::getInstance()->startTransaction()) {         
        // Insert the old duty leave (copy duty leave) 
        $condition = " classId = '".$REQUEST_DATA['classId']."' AND 
                       groupId = '".$REQUEST_DATA['groupId']."' AND 
                       subjectId = '".$REQUEST_DATA['subjectId']."' AND 
                       dutyDate = '".$REQUEST_DATA['forDate']."' AND 
                       periodId = '".$REQUEST_DATA['periodId']."' AND
                       instituteId = '".$instituteId."' AND
                       sessionId = '".$sessionId."' ";
        $ret=$teacherManager->copyDutyLeave($condition,$REQUEST_DATA['targetPeriodId']);                    
        if($ret===false){
          echo FAILURE;  
          die;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo SUCCESS;
            die;
        }
        else {
            echo FAILURE;
        }
    }
    else {
      echo FAILURE;
    }
    
    /*
        if($REQUEST_DATA['targetPeriodId']!=-1){//for daily attendance,update duty_leave table also
            $sessionId=$sessionHandler->getSessionVariable('SessionId');
            $instituteId=$sessionHandler->getSessionVariable('InstituteId');
             
            $ret=$teacherManager->updateDutyLeaveClassWise($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['forDate'],$REQUEST_DATA['targetPeriodId'],$sessionId,$instituteId);
            if($ret===false){
               die(FAILURE);
            }
        }
    */
    
?>