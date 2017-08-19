<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ReplaceTeacherTimeTable');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    /////////////////////////
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
    
    if($timeTableLabelId==''){
        echo SELECT_TIME_TABLE;
        die;
    }
    if($employeeId==''){
        echo 'Invalid teacher';
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

    $diff=$to_date - $from_date;   //from date cannot be smaller than to date
    if($diff<0){
        echo TIME_TABLE_DATE_VALIDATION;
        die;
    }
    
    //if both fromDate and toDate are smaller than current date
    $diff1=$server_date - $from_date; 
    $diff2=$server_date - $to_date;  
    if($diff1>0 and $diff2>0){
        echo TIME_TABLE_CANCEL_DATE_VALIDATION;
        die;
    }
    
    //show records where daysOfWeek falls between fromDate and toDate
    $daysOfWeekString='';
    for($i=0;$i<$diff;$i++){
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
    $daysOfWeekString= implode(',',array_unique(explode(',',$daysOfWeekString)));
    
    
    $filter=' AND emp.employeeId='.$employeeId.' AND ttl.isActive=1 AND ttl.timeTableLabelId='.$timeTableLabelId.' AND tt.daysOfWeek IN ('.$daysOfWeekString.') ';
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';

    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'daysOfWeek';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";

    ////////////
    
    $employeeRecordArray  = $timeTableManager->getAdjustedTeacherTimeTableForActiveTimeTable($filter,$orderBy);
    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       //fetch attendance records
       $groupId   = $employeeRecordArray[$i]['groupId'];
       $classId   = $employeeRecordArray[$i]['classId'];
       $subjectId = $employeeRecordArray[$i]['subjectId'];
       $fDate  = $employeeRecordArray[$i]['fromDate'];
       $tDate    = $employeeRecordArray[$i]['toDate'];
       
       $attendanceRecordsArray=$timeTableManager->fetchAttendanceRecords($employeeId,$classId,$groupId,$subjectId,$fDate,$tDate);
       //if attendance has taken for this adjustments
       if($attendanceRecordsArray[0]['totalRecords']>0){
           $attendanceTaken='<b class="redColor">Yes</b>';
           $alt="1";
       }
       else{
           $attendanceTaken="No";
           $alt="0";
       }
       $employeeRecordArray[$i]['daysOfWeek']=$daysArr[$employeeRecordArray[$i]['daysOfWeek']];
       $employeeRecordArray[$i]['fromDate']=UtilityManager::formatDate($employeeRecordArray[$i]['fromDate']);
       $employeeRecordArray[$i]['toDate']=UtilityManager::formatDate($employeeRecordArray[$i]['toDate']);
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1),'attendanceTaken'=>$attendanceTaken,"emps" => "<input type=\"checkbox\" alt=\"".$alt."\" name=\"cancelledEmps\" id=\"cancelledEmps".$employeeRecordArray[$i]['timeTableAdjustmentId']."\" value=\"".$employeeRecordArray[$i]['timeTableAdjustmentId'] ."\" >"), $employeeRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxGetAdjustedTimeTableDetailsForTeacher.php $
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