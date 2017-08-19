<?php
//-------------------------------------------------------
// Purpose: To display the records of display "Events in Parents" in array from the database, pagination and search  functionality
//
// Author : Arvind Singh Rawat
// Created on : 14-07-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ParentDisplayInstituteEvents');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (eventTitle LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR shortDescription LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR startDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR endDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'startDate';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $parentManager->getTotalEvents($filter);
    $parentRecordArray = $parentManager->getEvents($filter,$limit,$orderBy);   
   
    $cnt = count($parentRecordArray);
   
    for($i=0;$i<$cnt;$i++) {
        // add countryId in actionId to populate edit/delete icons in User Interface   
       // $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$parentRecordArray[$i]);
       $parentRecordArray[$i]['startDate'] = UtilityManager::formatDate($parentRecordArray[$i]['startDate']);
       $parentRecordArray[$i]['endDate'] = UtilityManager::formatDate($parentRecordArray[$i]['endDate']);
       UtilityManager::formatDate($SMSDetailRecordArray[$i]['dated']);
       
       if(strlen($parentRecordArray[$i]['eventTitle']) >=35) {
         $parentRecordArray[$i]['eventTitle'] = substr($parentRecordArray[$i]['eventTitle'],0,35)."....";
       }
       if(strlen($parentRecordArray[$i]['shortDescription']) >=70) {
          $parentRecordArray[$i]['shortDescription'] = substr($parentRecordArray[$i]['shortDescription'],0,70)."....";
       }
       $valueArray = array_merge( array('Edit'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/detail.gif"  border="0" onClick="editWindow('.$parentRecordArray[$i]['eventId'].',\'ViewEvents\',520,400); return false;"/></a>','action' => $parentRecordArray[$i]['eventId'] , 'srNo' => ($records+$i+1) ),$parentRecordArray[$i]);     
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

  
//$History : ajaxInitDisplayEvents.php $
//  

?>