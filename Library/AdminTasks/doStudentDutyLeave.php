<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
define('MODULE','DutyLeaves');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

$teacherManager = TeacherManager::getInstance();
$employeeId=$REQUEST_DATA['employeeId'];
$userId=$sessionHandler->getSessionVariable('UserId');
$serverDate=date('Y-m-d');

//function to check date difference
function dateDiff($dformat, $endDate, $beginDate)
{
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
}

$dtArr=array();
//to check duplicate date value
function checkDuplicateDate($value){
    $fl=1;
    global $dtArr;
    $len=count($dtArr);
    for($i=0;$i<$len;$i++){
        if($dtArr[$i]==$value){
            $fl=0;
            break;
        }
    }
    
    if($fl==1){
        $dtArr[]=$value;
    }
    
    return $fl;
}    

if( trim($REQUEST_DATA['studentId'])!= '' && trim($REQUEST_DATA['classId'])!= '' && trim($REQUEST_DATA['groupId'])!= '' && trim($REQUEST_DATA['subjectId'])!= '') {
 
    //first check whether this student exists in this combination;
    $filter =" AND c.classId=".$REQUEST_DATA['classId']." AND g.groupId=".$REQUEST_DATA['groupId']." AND sc.subjectId=".$REQUEST_DATA['subjectId']." AND s.studentId=".$REQUEST_DATA['studentId']; 
    $studentArr=$teacherManager->getStudentDutyLeaveList($filter);
    if(trim($REQUEST_DATA['studentId'])!=$studentArr[0]['studentId']){
        echo STUDENT_NOT_EXIST;
        die;
    }
    
    //then check date condition.no two leaves for a same subject on same day
    $dtLen=0;
    $dtStr=explode(',',$REQUEST_DATA['dates']);
    if($REQUEST_DATA['dates']!=''){
     $dtLen=count($dtStr);
    }
    $comStr=explode('~!~@@~!~',$REQUEST_DATA['comments']);

    for($i=0;$i<$dtLen;$i++){
        if(!checkDuplicateDate($dtStr[$i])){
            echo DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION;
            die;
        }
        if(dateDiff('-',$serverDate,$dtStr[$i]) < 0){
            echo DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION2;
            die;
        }
        if(trim($comStr[$i])==''){
           echo ENTER_YOUR_COMMENTS;
           die;
        }
    }
    
    $ldStr=explode(',',$REQUEST_DATA['leaves']);
    
    //find timeTableLabelId of the class
    $filter1 =" WHERE classId=".$REQUEST_DATA['classId']; 
    $timeTableArr=$teacherManager->getClassTimeTableLabel($filter1);
    if($timeTableArr[0]['timeTableLabelId']==''){
        echo TIME_TABLE_LABEL_NOT_FOUND;
        die;
    }
    $ttlId=$timeTableArr[0]['timeTableLabelId'];
    

 //****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {    

        //delete duty leaves 
        $ret1=TeacherManager::getInstance()->deleteDutyLeaves(trim($REQUEST_DATA['studentId']), trim($REQUEST_DATA['classId']),trim($REQUEST_DATA['groupId']),trim($REQUEST_DATA['subjectId']));
        if($ret1===false){
            echo FAILURE;
            die;
        }
        
        $query='';
        for($i=0;$i<$dtLen;$i++){
            if($query!=''){
                $query .=',';
            }
            $query .=" ( $ttlId, $REQUEST_DATA[studentId], $ldStr[$i] , '".$dtStr[$i]."','".add_slashes(trim($comStr[$i]))."', $REQUEST_DATA[classId], $REQUEST_DATA[subjectId], $REQUEST_DATA[groupId], $employeeId, $userId )";
        }
        
        if($query!=''){
            //insert duty leaves
            $ret2=TeacherManager::getInstance()->insertDutyLeaves($query);
            if($ret2===false){
                echo FAILURE;
                die;
            }
        }

      //*****************************COMMIT TRANSACTION************************* 
         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo   SUCCESS;
            die;
         }
         else {
            echo FAILURE;
            die;
         }
      
    }
    else{
          echo FAILURE;
          die;
    }   
}
else{
    echo FAILURE;
    die;
}
// $History: doStudentDutyLeave.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 27/05/09   Time: 12:32
//Updated in $/LeapCC/Library/AdminTasks
//Added "comments" field in duty leave module in admin & teacher section
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Library/AdminTasks
//Created "Duty Leave" Module
?>