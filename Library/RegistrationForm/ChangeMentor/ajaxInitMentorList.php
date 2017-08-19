<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    
     require_once(MODEL_PATH . "/RegistrationForm/ChangeMentorManager.inc.php");
    $changeMentorManager = ChangeMentorManager::getInstance();
    
    $userId = $REQUEST_DATA['currentMentorId']; 
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 3500;
    $limit      = ' LIMIT '.$records.',3500';
    
 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $orderBy = " $sortField $sortOrderBy";         

    
    $filter = " AND sm.userId = '$userId'";
    
    $totalArray = $changeMentorManager->getStudentMentorCount($filter);
    $studentRecordArray = $changeMentorManager->getStudentMentorList($filter,$orderBy,$limit);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
       $id = $studentRecordArray[$i]['mentorshipId'];
        
       if($studentRecordArray[$i]['dateOfBirth']!=NOT_APPLICABLE_STRING) {
         $studentRecordArray[$i]['dateOfBirth'] = UtilityManager::formatDate($studentRecordArray[$i]['dateOfBirth']);  
       }
       if($studentRecordArray[$i]['permAddress']=='') {
         $studentRecordArray[$i]['permAddress'] = NOT_APPLICABLE_STRING;  
       }
       if($studentRecordArray[$i]['corrAddress']=='') {
         $studentRecordArray[$i]['corrAddress'] = NOT_APPLICABLE_STRING;  
       }     
       $checkall = '<input type="checkbox" name="chb[]"  value="'.$id.'">';
       $valueArray = array_merge(array(
                              'checkAll' => $checkall,
                              'srNo' => ($records+$i+1), 
                              'studentName' => strip_slashes($studentRecordArray[$i]['studentName']),
                              'rollNo' => strip_slashes($studentRecordArray[$i]['rollNo']),
                              'fatherName' => strip_slashes($studentRecordArray[$i]['fatherName']),
                              'dateOfBirth' =>  $studentRecordArray[$i]['dateOfBirth'],
                              'className' => strip_slashes($studentRecordArray[$i]['className']),
                              'studentMobileNo' => strip_slashes($studentRecordArray[$i]['studentMobileNo']),    
                              'permAddress' => strip_slashes($studentRecordArray[$i]['permAddress']),
                              'studentPhoto' => strip_slashes($studentRecordArray[$i]['studentPhoto'])));
                              
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>