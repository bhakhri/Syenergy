<?php
//-------------------------------------------------------
// Purpose: To fetch student marks details
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SearchStudentDisplay');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $startDate = $REQUEST_DATA['startDate'];
    $endDate   = $REQUEST_DATA['endDate'];
    
    if($startDate)
        $conditions = " AND fromDate BETWEEN '$startDate' AND '$endDate'";
    if($endDate)
        $conditions .= " AND toDate BETWEEN '$startDate' AND '$endDate'";

            
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    if(trim($sessionHandler->getSessionVariable('rStudentId'))!=''){
        if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
          $totalArray                   = $teacherManager->getConsolidatedTotalStudentAttendance($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$conditions);
          $studentAttendanceRecordArray = $teacherManager->getConsolidatedStudentAttendance($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$conditions,$orderBy,$limit);
        }
        else{//if group wise display is required
          $totalArray                   = $teacherManager->getTotalStudentAttendance($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$conditions);
          $studentAttendanceRecordArray = $teacherManager->getStudentAttendance($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$conditions,$orderBy,$limit);    
        }
    }
    $cnt = count($studentAttendanceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
       if($studentAttendanceRecordArray[$i]['delivered'] >0 and $studentAttendanceRecordArray[$i]['attended'] >0)
            $percentageAtt = "".round(($studentAttendanceRecordArray[$i]['attended']/$studentAttendanceRecordArray[$i]['delivered'])*100,2)."";
        else
            $percentageAtt = "0";
 
        $valueArray = array_merge(array('srNo' => ($records+$i+1),'percentage'=>$percentageAtt ),$studentAttendanceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
   if($REQUEST_DATA['consolidatedView']==0){ 
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
   }
   else{
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
   }
    
// for VSS
// $History: ajaxAttendanceList.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/11/09    Time: 12:30
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Fixed Query Error
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/09/09   Time: 11:13
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Done bug fixng.
//bug ids---
//00001503
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:37p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//Corrected Student Tabs
?>
