<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To display attendance list
// Author : Dipanjan Bbhattacharjee
// Created on : (14.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DeleteAttendance');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $adminTasksManager = AdminTasksManager::getInstance();
    
    if($REQUEST_DATA['subjectId']=='' OR $REQUEST_DATA['classId']=='' OR $REQUEST_DATA['timeTableLabelId']==''){
        echo FAILURE;
        die;
    }

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = 'LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;
    //////
    
    /////search functionility not needed   
    $filter="";

    if(trim($REQUEST_DATA['subjectId'])!=""){
        $filter .= " AND att.subjectId=".trim($REQUEST_DATA['subjectId']); 
    }
    if(trim($REQUEST_DATA['classId'])!=""){
        $filter .= " AND att.classId=".trim($REQUEST_DATA['classId']); 
    }
    if(trim($REQUEST_DATA['timeTableLabelId'])!=""){
        $filter .= " AND ttc.timeTableLabelId=".trim($REQUEST_DATA['timeTableLabelId']); 
    }
    
    
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = " $sortField $sortOrderBy";   
     //echo  $orderBy;

    ////////////
    
    $totalArray         = $adminTasksManager->getTotalAttendance($filter);
    $attendanceRecordArray = $adminTasksManager->getAttendanceList($filter,$limit,$orderBy);
    $cnt = count($attendanceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
        
       $attType=$attendanceRecordArray[$i]['attendanceType'];
       $fromDate=$attendanceRecordArray[$i]['fromDate']; 
       $toDate=$attendanceRecordArray[$i]['toDate'];
        
       if($attendanceRecordArray[$i]['attendanceType']==1){
           $attendanceRecordArray[$i]['attendanceType']='Bulk';
       } 
       elseif($attendanceRecordArray[$i]['attendanceType']==2){
           $attendanceRecordArray[$i]['attendanceType']='Daily';
       }
       
       $attendanceRecordArray[$i]['fromDate']=UtilityManager::formatDate($attendanceRecordArray[$i]['fromDate']);
       $attendanceRecordArray[$i]['toDate']=UtilityManager::formatDate($attendanceRecordArray[$i]['toDate']);
       
       $valueArray = array_merge(
                        array(
                              'srNo' => ($records+$i+1),
                              "deleteAtt" => "<input type=\"checkbox\" name=\"attendance\" id=\"attendance\" value=\"".$attendanceRecordArray[$i]['employeeId'].'~'.$attendanceRecordArray[$i]['classId'].'~'.$attendanceRecordArray[$i]['subjectId'].'~'.$attendanceRecordArray[$i]['groupId'].'~'.$fromDate.'~'.$toDate.'~'.$attType.'~'.$attendanceRecordArray[$i]['periodId']."\">"
                             )
        , $attendanceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxDeleteAttendanceList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Administrator Date: 5/06/09    Time: 15:12
//Updated in $/LeapCC/Library/AdminTasks
//Corrected attendance deletion module's logic
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:21
//Created in $/LeapCC/Library/AdminTasks
//Created Attendance Delete Module
?>
