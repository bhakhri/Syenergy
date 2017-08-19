<?php
//-------------------------------------------------------
// Purpose: To store the records of training in array from the database, pagination and search, delete 
// functionality
//
// Author : Parveen
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','EmployeeInformation');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true); 
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/EmployeeManager.inc.php");
     $workshopManager = EmployeeManager::getInstance();
    /////////////////////////
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');           
    
    
     // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (w.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.sponsored LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.sponsoredDetail LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.location LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'topic';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND w.employeeId = '.add_slashes($employeeId);
     
    ////////////
    $totalArray = $workshopManager->getTotalWorkshop($condition);
    $workshopRecordArray = $workshopManager->getWorkshopList($condition,$orderBy,$limit);
    $cnt = count($workshopRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $id = $workshopRecordArray[$i]['workshopId'];        
       
       if($workshopRecordArray[$i]['startDate']=='0000-00-00') {
         $workshopRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
       }
       else {
         $workshopRecordArray[$i]['startDate'] = UtilityManager::formatDate($workshopRecordArray[$i]['startDate']);
       }
        
       if($workshopRecordArray[$i]['endDate']=='0000-00-00') {
         $workshopRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
       }
       else {
         $workshopRecordArray[$i]['endDate'] = UtilityManager::formatDate($workshopRecordArray[$i]['endDate']);
       }
        
       if($workshopRecordArray[$i]['sponsored']=='N') {
         $workshopRecordArray[$i]['sponsoredDetail'] = NOT_APPLICABLE_STRING;
       }
       else {
         $workshopRecordArray[$i]['sponsoredDetail'] = substr($workshopRecordArray[$i]['sponsoredDetail'],0,20)."....";              
       }
       
        if(strlen($workshopRecordArray[$i]['audience'])>20) {
           $workshopRecordArray[$i]['audience'] = substr($workshopRecordArray[$i]['audience'],0,20)."....";  
       }
       
       if(strlen($workshopRecordArray[$i]['topic'])>20) {
           $workshopRecordArray[$i]['topic'] = substr($workshopRecordArray[$i]['topic'],0,20)."....";  
       }
       
       if(strlen($workshopRecordArray[$i]['location'])>20) {
           $workshopRecordArray[$i]['location'] = substr($workshopRecordArray[$i]['location'],0,20)."....";  
       }
       
                   
       $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="workshopEditWindow('.$workshopRecordArray[$i]['workshopId'].',\'WorkShopActionDiv\',440,350);return false;" border="0"></a>&nbsp;
                   <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" alt="Delete" onclick="deleteWorkshop('.$workshopRecordArray[$i]['workshopId'].');return false;" border="0"></a>&nbsp;
                   <a href="#" title="Brief Description"><img src="'.IMG_HTTP_PATH.'/zoom.gif" alt="Brief Description" onclick="showWorkshopDetails('.$id.',\'divWorkshopInfo\',510,350);return false;" border="0"></a>';

       $valueArray = array_merge(array('action1' => $actionStr , 
                                        'srNo' => ($records+$i+1)),$workshopRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

    
// $History: ajaxInitWorkshopList.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//issue fix format & conditions & alignment updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/12/09    Time: 4:36p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//bug no. 400, 408, 405, 403 fix
//(formating condition format updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/17/09    Time: 5:26p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//role permission, alignment, new enhancements added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:42p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:41p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//
?>
