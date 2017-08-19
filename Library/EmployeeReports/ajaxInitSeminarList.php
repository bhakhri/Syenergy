<?php
//-------------------------------------------------------
// Purpose: To store the records of training in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/EmployeeManager.inc.php");
     $seminarManager = EmployeeManager::getInstance();
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    global $seminarParticipationArr;
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        foreach($seminarParticipationArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $participationId = " OR participationId LIKE '%$key%' ";
           break;
         }
       }       
       $condition = ' AND (s.organisedBy LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.seminarPlace LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.fee LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$participationId.')'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'organisedBy';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND s.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
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
       
       $id = $seminarRecordArray[$i]['seminarId'];
       
       if($seminarRecordArray[$i]['participationId']==0 || $seminarRecordArray[$i]['participationId']=="") {
           $seminarRecordArray[$i]['participationId'] = NOT_APPLICABLE_STRING;
       }
       else {
          $seminarRecordArray[$i]['participationId'] = $seminarParticipationArr[$seminarRecordArray[$i]['participationId']];      
       }
       
       if($seminarRecordArray[$i]['fee']==0 || $seminarRecordArray[$i]['fee']=="") {
           $seminarRecordArray[$i]['fee'] = 0;
       } 
        
       
       if(strlen($seminarRecordArray[$i]['organisedBy'])>=25) {
         $seminarRecordArray[$i]['organisedBy'] = substr($seminarRecordArray[$i]['organisedBy'],0,25)."....";  
       }
       
       if(strlen($seminarRecordArray[$i]['seminarPlace'])>=35) {
         $seminarRecordArray[$i]['seminarPlace'] = substr($seminarRecordArray[$i]['seminarPlace'],0,35)."....";  
       }
       
       if(strlen($seminarRecordArray[$i]['topic'])>=35) {
         $seminarRecordArray[$i]['topic'] = substr($seminarRecordArray[$i]['topic'],0,35)."....";  
       }
       
       //$seminarRecordArray[$i]['organisedBy'] = '<a href="" name="bubble" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($seminarRecordArray[$i]['organisedBy'],0,25))).'...</a>';  
       //$seminarRecordArray[$i]['seminarPlace'] = '<a href="" name="bubble" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($seminarRecordArray[$i]['seminarPlace'],0,25))).'...</a>';  
       //$seminarRecordArray[$i]['topic'] = '<a href="" name="bubble" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($seminarRecordArray[$i]['topic'],0,25))).'...</a>';  
       
       
       $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="seminarEditWindow('.$seminarRecordArray[$i]['seminarId'].',\'SeminarActionDiv\',700,600);return false;" border="0"></a>&nbsp;
                   <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" alt="Delete" onclick="deleteSeminar('.$seminarRecordArray[$i]['seminarId'].');return false;" border="0"></a>&nbsp;
                   <a href="#" title="Brief Description"><img src="'.IMG_HTTP_PATH.'/zoom.gif" alt="Brief Description" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" border="0"></a>';
        
       
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
//*****************  Version 8  *****************
//User: Parveen      Date: 9/11/09    Time: 5:28p
//Updated in $/LeapCC/Library/EmployeeReports
//search condition updated 
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-08-25   Time: 5:09p
//Updated in $/LeapCC/Library/EmployeeReports
//Updated with Access rights DEFINE
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/12/09    Time: 3:25p
//Updated in $/LeapCC/Library/EmployeeReports
//alignment & formatting updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/05/09    Time: 5:31p
//Updated in $/LeapCC/Library/EmployeeReports
//bug fix: (search condition) updated condition format updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/16/09    Time: 5:13p
//Updated in $/LeapCC/Library/EmployeeReports
//new enhancements Div Base (Berif information browse)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Library/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/13/09    Time: 12:34p
//Created in $/LeapCC/Library/EmployeeReports
//file added
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
