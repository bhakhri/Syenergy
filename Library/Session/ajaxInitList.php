<?php
//-------------------------------------------------------
// Purpose: To store the records of Session in array from the database, pagination and search, delete 
// functionality
//
// Author : Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SessionMaster');  
    define('ACCESS','view'); 
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SessionsManager.inc.php");   
    $sessionsManager = SessionsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
         $filter = '  WHERE sessionName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sessionYear LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"';  
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
        $sessionRecordArray[$i]['startDate'] =strip_slashes($sessionRecordArray[$i]['startDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :        UtilityManager::formatDate($sessionRecordArray[$i]['startDate']);
        
        $sessionRecordArray[$i]['endDate'] = strip_slashes($sessionRecordArray[$i]['endDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($sessionRecordArray[$i]['endDate']);
                
        $valueArray = array_merge(array('action' => $sessionRecordArray[$i]['sessionId'] , 'srNo' => ($records+$i+1) ),$sessionRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/12/09    Time: 12:16p
//Updated in $/LeapCC/Library/Session
//start date added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Session
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/15/08   Time: 10:50a
//Updated in $/Leap/Source/Library/Session
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 11:51a
//Updated in $/Leap/Source/Library/Session
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/21/08    Time: 10:20a
//Updated in $/Leap/Source/Library/Session
//Added last line comment for VSS


?>
