<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Parveen Sharma
// Created on : (18.03.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ThoughtsMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ThoughtsManager.inc.php");
    $thoughtsManager = ThoughtsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (thought LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'thought';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $thoughtsManager->getTotalThoughts($filter);
    $thoughtsRecordArray = $thoughtsManager->getThoughtsList($filter,$limit,$orderBy);
    $cnt = count($thoughtsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(strlen(stripslashes(strip_tags($thoughtsRecordArray[$i]['thought'])))>=150) {
            $id = $thoughtsRecordArray[$i]['thoughtId'];
            $thoughtsRecordArray[$i]['thought'] = '<a href="" name="bubble" onclick="showThoughtsDetails('.$id.',\'divThoughts\',405,200);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($thoughtsRecordArray[$i]['thought'],0,100))).'...</a>';  
        }
        $valueArray = array_merge(array('action' => $thoughtsRecordArray[$i]['thoughtId'] , 'srNo' => ($records+$i+1) ),$thoughtsRecordArray[$i]);
    
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/07/09    Time: 1:24p
//Updated in $/LeapCC/Library/Thoughts
//Brief Description length settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:09a
//Created in $/LeapCC/Library/Thoughts
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 6:31p
//Created in $/Leap/Source/Library/Thoughts
//file added
//
?>
