<?php
//-------------------------------------------------------
// Purpose: To store the records of training in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeInfo');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/SeminarsManager.inc.php");
     $seminarManager = SeminarsManager::getInstance();
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (s.organisedBy LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.seminarPlace LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'organisedBy';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND s.employeeId = '.$REQUEST_DATA['employeeId'];
     
    ////////////
    $totalArray = $seminarManager->getTotalSeminars($condition);
    $seminarRecordArray = $seminarManager->getSeminarsList($condition,$orderBy,$limit);
    $cnt = count($seminarRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        if($seminarRecordArray[$i]['startDate']=='0000-00-00') {
           $seminarRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $seminarRecordArray[$i]['startDate'] = UtilityManager::formatDate($seminarRecordArray[$i]['startDate']);
        }
        
        if($seminarRecordArray[$i]['endDate']=='0000-00-00') {
           $seminarRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $seminarRecordArray[$i]['endDate'] = UtilityManager::formatDate($seminarRecordArray[$i]['endDate']);
        }
       
       $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="seminarEditWindow('.$seminarRecordArray[$i]['seminarId'].',\'SeminarActionDiv\',700,600);return false;" border="0">
        <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteSeminar('.$seminarRecordArray[$i]['seminarId'].');return false;"></a>';
        
       
       $valueArray = array_merge(array('action1' => $actionStr , 
                                        'srNo' => ($records+$i+1)),$seminarRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

    
// $History: ajaxInitSeminarList.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Seminar
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Seminar
//initial checkin 
//

?>
