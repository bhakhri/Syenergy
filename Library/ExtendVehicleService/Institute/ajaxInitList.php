<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','InstituteMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/InstituteManager.inc.php");
    $instituteManager = InstituteManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ins.instituteName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteWebsite LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.employeePhone LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteEmail LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'instituteName';
    
     $orderBy = " ins.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $instituteManager->getTotalInstitute($filter);
    $instituteRecordArray = $instituteManager->getInstituteList($filter,$limit,$orderBy);
    $cnt = count($instituteRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $instituteRecordArray[$i]['instituteId'] , 'srNo' => ($records+$i+1) ),$instituteRecordArray[$i]);

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
//User: Administrator Date: 8/06/09    Time: 14:13
//Updated in $/LeapCC/Library/Institute
//Done bug fixing.
//bug ids---> 1318 to 1329 ,Leap bugs4.doc(5 to 10,12,20)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Institute
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Library/Institute
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Institute
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/30/08    Time: 2:51p
//Created in $/Leap/Source/Library/Institute
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/30/08    Time: 2:40p
//Updated in $/Leap/Source/Library/institute
//Added AjaxListing and AjaxSearch Funcationality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:31a
//Created in $/Leap/Source/Library/institute
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
