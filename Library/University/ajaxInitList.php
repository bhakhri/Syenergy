<?php
//-------------------------------------------------------
// Purpose: To store the records of universities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','UniversityMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/UniversityManager.inc.php");
    $universityManager = UniversityManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (un.universityName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.universityCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.universityAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.universityWebsite LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.contactPerson LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.contactNumber LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.universityEmail LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityName';
    
     $orderBy = " un.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $universityManager->getTotalUniversity($filter);
    $universityRecordArray = $universityManager->getUniversityList($filter,$limit,$orderBy);
    $cnt = count($universityRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $universityRecordArray[$i]['universityId'] , 'srNo' => ($records+$i+1) ),$universityRecordArray[$i]);

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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/10/09   Time: 17:38
//Updated in $/LeapCC/Library/University
//Done bug fixing.
//Bug ids---
//00001857
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 15:22
//Updated in $/LeapCC/Library/University
//Corrected bugs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/University
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:16a
//Updated in $/Leap/Source/Library/University
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Library/University
//Added Image upload functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:09a
//Created in $/Leap/Source/Library/University
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:35p
//Updated in $/Leap/Source/Library/University
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/30/08    Time: 2:51p
//Created in $/Leap/Source/Library/University
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/30/08    Time: 2:40p
//Updated in $/Leap/Source/Library/university
//Added AjaxListing and AjaxSearch Funcationality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:31a
//Created in $/Leap/Source/Library/university
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Library/TestType
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/30/08    Time: 10:39a
//Created in $/Leap/Source/Library/TestType
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Library/City
//Added AjaxList Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/27/08    Time: 5:08p
//Created in $/Leap/Source/Library/City
//Initial Checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
  
?>
