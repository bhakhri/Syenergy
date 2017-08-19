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
    define('MODULE','EmployeeInformation');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $consultManager = EmployeeManager::getInstance();
    /////////////////////////
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');        
    
     // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (c.projectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.sponsorName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.remarks LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'projectName';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND c.employeeId = '.add_slashes($employeeId);
     
    ////////////
    $totalArray = $consultManager->getTotalConsulting($condition);
    $consultRecordArray = $consultManager->getConsultingList($condition,$orderBy,$limit);
    $cnt = count($consultRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
         $id = $consultRecordArray[$i]['consultId'];        
        
        if($consultRecordArray[$i]['startDate']=='0000-00-00') {
           $consultRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $consultRecordArray[$i]['startDate'] = UtilityManager::formatDate($consultRecordArray[$i]['startDate']);
        }
        
        if($consultRecordArray[$i]['endDate']=='0000-00-00') {
           $consultRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $consultRecordArray[$i]['endDate'] = UtilityManager::formatDate($consultRecordArray[$i]['endDate']);
        }
       
        
        if(strlen($consultRecordArray[$i]['projectName'])>20) {
           $consultRecordArray[$i]['projectName']  = substr($consultRecordArray[$i]['projectName'],0,20)."....";  
        }
        
        if(strlen($consultRecordArray[$i]['sponsorName'])>20) {
           $consultRecordArray[$i]['sponsorName']  = substr($consultRecordArray[$i]['sponsorName'],0,20)."....";  
        }
        
        if(strlen($consultRecordArray[$i]['remarks'])>20) {
           $consultRecordArray[$i]['remarks']  = substr($consultRecordArray[$i]['remarks'],0,20)."....";  
        }
        
        if( $consultRecordArray[$i]['amountFunding']=='') {
           $consultRecordArray[$i]['amountFunding'] = 0;              
        }
        
        //$actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="consultingEditWindow('.$consultRecordArray[$i]['consultId'].',\'ConsultingActionDiv\',700,600);return false;" border="0">
        //<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteConsulting('.$consultRecordArray[$i]['consultId'].');return false;"></a>';
      
        $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="consultingEditWindow('.$consultRecordArray[$i]['consultId'].',\'ConsultingActionDiv\',700,600);return false;" border="0"></a>&nbsp;
                    <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" alt="Delete" onclick="deleteConsulting('.$consultRecordArray[$i]['consultId'].');return false;" border="0"></a>&nbsp;
                    <a href="#" title="Brief Description"><img src="'.IMG_HTTP_PATH.'/zoom.gif" alt="Brief Description" onclick="showConsultingDetails('.$id.',\'divConsultingInfo\',505,350);return false;" border="0"></a>';

       
       $valueArray = array_merge(array('action1' => $actionStr , 
                                        'srNo' => ($records+$i+1)),$consultRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

    
// $History: ajaxInitConsultingList.php $
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
//User: Parveen      Date: 7/13/09    Time: 3:39p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity/Consulting
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 12:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity/Consulting
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Consulting
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Consulting
//initial checkin 
//

?>
