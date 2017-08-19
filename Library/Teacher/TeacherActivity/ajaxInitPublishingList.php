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
    define('MODULE','EmployeeInformation');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);   
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $publishingManager = EmployeeManager::getInstance();

    /////////////////////////
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');         
      // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
      
    global $publisherScopeArr;  
      
   // Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       foreach($publisherScopeArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $scopeId = " OR scopeId LIKE '%$key%' ";
           break;
         }
       }      
       $condition = ' AND (p.type LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.publishedBy LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.description LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$scopeId.')';
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'type';
    
    $orderBy = " $sortField $sortOrderBy";         

    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');       
    $condition .= ' AND p.employeeId = '.add_slashes($employeeId);
    
     
    ////////////
    $totalArray = $publishingManager->getTotalPublishing($condition);
    $publishingRecordArray = $publishingManager->getPublishingList($condition,$orderBy,$limit);
    $cnt = count($publishingRecordArray);
    
    global $publisherScopeArr;
    
    for($i=0;$i<$cnt;$i++) {
        $id = $publishingRecordArray[$i]['publishId'];
        
        if($publishingRecordArray[$i]['publishOn']=='0000-00-00') {
           $publishingRecordArray[$i]['publishOn'] = NOT_APPLICABLE_STRING;
        }
        else {
           $publishingRecordArray[$i]['publishOn'] = UtilityManager::formatDate($publishingRecordArray[$i]['publishOn']);
        }
        
        if($publishingRecordArray[$i]['scopeId']==0 || $publishingRecordArray[$i]['scopeId']=="") {
          $publishingRecordArray[$i]['scopeId'] = NOT_APPLICABLE_STRING;
        }
        else {
          $publishingRecordArray[$i]['scopeId'] = $publisherScopeArr[$publishingRecordArray[$i]['scopeId']];      
        }
        
       if(strlen($publishingRecordArray[$i]['type'])>=15) {
           $publishingRecordArray[$i]['type'] = substr($publishingRecordArray[$i]['type'],0,15)."...."; 
        }
        
        if(strlen($publishingRecordArray[$i]['publishedBy'])>=25) {
           $publishingRecordArray[$i]['publishedBy'] = substr($publishingRecordArray[$i]['publishedBy'],0,25)."...."; 
        }
        
        if(strlen($publishingRecordArray[$i]['description'])>=25) {
           $publishingRecordArray[$i]['description'] = substr($publishingRecordArray[$i]['description'],0,25)."...."; 
        }
        
        $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editWindow('.$id.',\'PublishingActionDiv\',720,600);return false;" border="0"></a>&nbsp;
                    <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" alt="Delete" onclick="deletePublishing('.$id.');return false;" border="0"></a>&nbsp;
                    <a href="#" title="Brief Description"><img src="'.IMG_HTTP_PATH.'/zoom.gif" alt="Brief Description" onclick="showPublisherDetails('.$id.',\'divPublisherInfo\',505,350);return false;" border="0"></a>';

        $publishingRecordArray[$i]['attachmentFile'] = (strip_slashes($publishingRecordArray[$i]['attachmentFile'])=='' ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($publishingRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download" />');    
        $publishingRecordArray[$i]['attachmentAcceptationLetter'] = (strip_slashes($publishingRecordArray[$i]['attachmentAcceptationLetter'])=='' ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($publishingRecordArray[$i]['attachmentAcceptationLetter']).'" onclick="download(this.name);" title="Download" />');    

        $valueArray = array_merge(array('action1' => $actionStr , 
                                        'srNo' => ($records+$i+1)),$publishingRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitPublishingList.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 9/01/09    Time: 5:06p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//search condition updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 9/01/09    Time: 12:56p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//scopeId checks updated & file format correct (workshopList)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/21/09    Time: 12:18p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//function added stristr
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/20/09    Time: 12:45p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Gurkeerat: resolved issue 1116
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/12/09    Time: 4:36p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//bug no. 400, 408, 405, 403 fix
//(formating condition format updated)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/21/09    Time: 12:41p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//new enhancement added "attachmentAcceptationLetter" in Employee
//Publisher tab 
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
//Updated in $/LeapCC/Library/Teacher/TeacherActivity/Publishing
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 12:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity/Publishing
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Library/Publishing
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Library/Publishing
//initial checkin 
//
?>
