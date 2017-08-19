<?php
//-------------------------------------------------------
// Purpose: To store the records of Session in array from the database, pagination and search, delete 
// functionality
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');  
    define('ACCESS','view'); 
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(true);

    require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");   
    $sessionsManager = LeaveSessionsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = '  WHERE sessionName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                         IF(active=1,"Yes","No") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                         DATE_FORMAT(sessionStartDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                         DATE_FORMAT(sessionEndDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sessionName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $sessionsManager->getTotalSession($filter);
    $sessionRecordArray = $sessionsManager->getSessionList($filter,$limit,$orderBy);
	
    $cnt = count($sessionRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add sessionId in actionId to populate edit/delete icons in User Interface   
        $sessionRecordArray[$i]['sessionStartDate'] =strip_slashes($sessionRecordArray[$i]['sessionStartDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING : UtilityManager::formatDate($sessionRecordArray[$i]['sessionStartDate']);
        $sessionRecordArray[$i]['sessionEndDate'] = strip_slashes($sessionRecordArray[$i]['sessionEndDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($sessionRecordArray[$i]['sessionEndDate']);
        $valueArray = array_merge(array('action' => $sessionRecordArray[$i]['leaveSessionId'] , 
                                        'srNo' => ($records+$i+1) ),
                                        $sessionRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
