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
    
    $diff=$server_date - $from_date; //from date cannot be smaller than current date
    if($diff>0){
        echo TIME_TABLE_FROM_DATE_VALIDATION;
        die;
    }
    
    $diff=$server_date - $to_date;  //to date cannot be smaller than current date
    if($diff>0){
        echo TIME_TABLE_TO_DATE_VALIDATION;
        die;
    }
    
    $diff=$to_date - $from_date;   //from date cannot be smaller than to date
    if($diff<0){
        echo TIME_TABLE_DATE_VALIDATION;
        die;
    }
    
    //show records where daysOfWeek falls between fromDate and toDate
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
    $daysOfWeekString= implode(',',array_unique(explode(',',$daysOfWeekString)));
    
    
    //$filter=' AND emp.employeeId='.$employeeId.' AND ttl.isActive=1 AND ttl.timeTableLabelId='.$timeTableLabelId.' AND tt.daysOfWeek IN ('.$daysOfWeekString.') ';
    $filter=' AND emp.employeeId='.$employeeId.' AND ttl.timeTableLabelId='.$timeTableLabelId.' AND tt.daysOfWeek IN ('.$daysOfWeekString.') ';
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';

    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'daysOfWeek';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";

    ////////////
    
    $employeeRecordArray  = $timeTableManager->getTeacherTimeTableForActiveTimeTable($filter,$orderBy);
    $cnt = count($employeeRecordArray);
    for($i=0;$i<$cnt;$i++) {
       $employeeRecordArray[$i]['daysOfWeek']=$daysArr[$employeeRecordArray[$i]['daysOfWeek']];
        
       //$valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"emps\" id=\"emps\" value=\"".$employeeRecordArray[$i]['timeTableId'] ."\" onclick=\"generateSuggestionDiv(this.value,'".$employeeRecordArray[$i]['className']."','".$employeeRecordArray[$i]['subjectCode']."','".$employeeRecordArray[$i]['groupShort']."','".$employeeRecordArray[$i]['periodNumber']."','".$employeeRecordArray[$i]['daysOfWeek']."')\">"), $employeeRecordArray[$i]);
       $alt=$employeeRecordArray[$i]['className'].",".$employeeRecordArray[$i]['subjectCode'].",".$employeeRecordArray[$i]['groupShort'].",".$employeeRecordArray[$i]['periodNumber'].",".$employeeRecordArray[$i]['daysOfWeek'].",".trim($REQUEST_DATA['type']);
       
       //if these records are coming from adjusted table then these records cannot be selected
       if($employeeRecordArray[$i]['ttype']==2){
           //$disable='disabled="disabled"';
           $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => NOT_APPLICABLE_STRING), $employeeRecordArray[$i]);
       }
       else{
           $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"emps".trim($REQUEST_DATA['type'])."\" id=\"emps".$employeeRecordArray[$i]['timeTableId']."\" value=\"".$employeeRecordArray[$i]['timeTableId'] ."\" alt=\"".$alt."\" onclick=\"generateSuggestionDiv(this.value,this.alt,this.checked)\">"), $employeeRecordArray[$i]);
       }
       

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxGetTimeTableDetailsForTeacher.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 2/11/09    Time: 18:10
//Updated in $/LeapCC/Library/TimeTable
//Done bug fixing.
//Bug ids---
//00001919,00001920,00001921,00001923,00001924
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/11/09    Time: 16:24
//Updated in $/LeapCC/Library/TimeTable
//Removed check for active time table labels
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Library/TimeTable
//Added code for "Time table adjustment"
?>