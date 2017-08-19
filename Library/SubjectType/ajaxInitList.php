<?php
//-------------------------------------------------------
// Purpose: To store the records of SubjectType in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
    $subjectTypeManager = SubjectTypeManager::getInstance();
    
    define('MODULE','SubjectTypesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (sub.subjectTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectTypeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR uni.universityName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectTypeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $subjectTypeManager->getTotalSubjectType($filter);
    $subjectTypeRecordArray = $subjectTypeManager->getSubjectTypeList($filter,$limit,$orderBy);
	
    $cnt = count($subjectTypeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add subjectTypeId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $subjectTypeRecordArray[$i]['subjectTypeId'] , 'srNo' => ($records+$i+1) ),$subjectTypeRecordArray[$i]);

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
//*****************  Version 7  *****************
//User: Parveen      Date: 8/24/09    Time: 11:34a
//Updated in $/LeapCC/Library/SubjectType
//search in all fields any format type
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Library/SubjectType
//formatting & role permission added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/SubjectType
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:21p
//Updated in $/LeapCC/Library/SubjectType
//fixed bug nos.0000800,0000802,0000801,0000776,0000775,0000776,0000801
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/12/09    Time: 6:02p
//Updated in $/LeapCC/Library/SubjectType
//sorting order updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/01/09    Time: 12:56p
//Updated in $/LeapCC/Library/SubjectType
//list formatting & required field validation added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectType
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:46p
//Created in $/Leap/Source/Library/SubjectType
//added two new files
//1) ajaxInitDelete.php which performs delete function through ajax
//caaling
//2) ajaxInitList function which performs ajax table listing

  
?>
