<?php

//The file contains functions to get student attendance
//
// Author :Parveen Sharma
// Created on : 15.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    
    global $sessionHandler;
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
        
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
        
    $orderBy = " $sortField $sortOrderBy";  

    $studentId= trim($sessionHandler->getSessionVariable('StudentId'));
    $attendance = trim($sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'));
    $fromDate = trim($REQUEST_DATA['startDate']);
    $toDate = trim($REQUEST_DATA['toDate']);
    $classId = trim($REQUEST_DATA['studyPeriodId']);
    
    if($classId=='') {
      $classId = 0;  
    }

    if($studentId=='') {
      $studentId = 0;  
    }
    
    //$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
    //$classId = $classIdArray[0]['classId'];

    if($fromDate) {
            $where = " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
    }
    if($toDate) {
            $where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
    }

    if($where != "") {
      $where .= " AND su.hasAttendance = 1 ";
      if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");            
      }
     else{ //if group wise display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     }
    }
    else {
      $where .= " AND su.hasAttendance = 1 ";
     if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required 
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");
     }
     else{//if group wise display is required 
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     }
    }

    $cnt = count($studentInformationArray);
    
    for($i=0;$i<$cnt;$i++) {
        if ($studentInformationArray[$i]['studentName'] != '-1') {
            $studentInformationArray[$i]['Percentage'] = "0.00";
        }
        else {
            $studentInformationArray[$i]['Percentage'] = NOT_APPLICABLE_STRING;
            $studentInformationArray[$i]['Percentage'] = "<span style='color:#0081D7'><strong>".$studentInformationArray[$i]['Percentage']."</strong></span>";
        }


        if($studentInformationArray[$i]['studentName'] != '-1') {
            $studentInformationArray[$i]['fromDate'] = UtilityManager::formatDate($studentInformationArray[$i]['fromDate']);    
            $studentInformationArray[$i]['toDate'] = UtilityManager::formatDate($studentInformationArray[$i]['toDate']);
        }
        if($studentInformationArray[$i]['delivered'] > 0 ) {
            if ($studentInformationArray[$i]['dutyLeave'] != '') {
                $studentInformationArray[$i]['attended1'] = "".$studentInformationArray[$i]['attended'] + $studentInformationArray[$i]['dutyLeave']."";
                $studentInformationArray[$i]['Percentage']="".ROUND((($studentInformationArray[$i]['attended1'] /  $studentInformationArray[$i]['delivered'])*100),2)."";
            }
            else {
                $studentInformationArray[$i]['Percentage']="".ROUND((($studentInformationArray[$i]['attended'] /  $studentInformationArray[$i]['delivered'])*100),2)."";
            }
            
        }

        if ($studentInformationArray[$i]['dutyLeave'] == 'null' || $studentInformationArray[$i]['dutyLeave'] == '') {
            $studentInformationArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
        }
        else {
            $studentInformationArray[$i]['dutyLeave'] = "<span style='color:#000000'><strong>".$studentInformationArray[$i]['dutyLeave']."</strong></span>";
        }
        // add subjectId in actionId to populate edit/delete icons in User Interface   
        
            if ($studentInformationArray[$i]['Percentage'] <= $attendance ) {
                if ($studentInformationArray[$i]['studentName'] != '-1') {
                    $percentage = "<span class='padding_right'><span style='color:red'>".$studentInformationArray[$i]['Percentage']."</span></span>";
                }
                else {
                    $percentage = "<span class='padding_right'><span style='color:black'>".$studentInformationArray[$i]['Percentage']."</span></span>";
                }
            }
            else {
                $percentage = $studentInformationArray[$i]['Percentage'];
            }
        
        $valueArray = array_merge(array('per' => $percentage,'srNo' => ($records+$i+1) ),$studentInformationArray[$i]);
        
         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    //print_r($studentInformationArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}';

//$History: ajaxStudentAttendance.php $
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 14/12/09   Time: 15:51
//Updated in $/LeapCC/Library/Parent
//Corrected "Attendance" display  problems in "Parent" login
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 12  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Library/Parent
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 10  *****************
//User: Parveen      Date: 9/02/09    Time: 2:15p
//Updated in $/LeapCC/Library/Parent
//attendance, course Info validation & format updated 
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/17/09    Time: 5:57p
//Updated in $/LeapCC/Library/Parent
//date format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/17/09    Time: 2:24p
//Updated in $/LeapCC/Library/Parent
//validation, formatting, link tabs updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/18/08   Time: 5:25p
//Updated in $/LeapCC/Library/Parent
//code update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/17/08   Time: 5:05p
//Updated in $/LeapCC/Library/Parent
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/16/08   Time: 5:15p
//Updated in $/LeapCC/Library/Parent
//attendance update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/16/08   Time: 10:44a
//Updated in $/LeapCC/Library/Parent
//Intial Checkin 
//

?>
