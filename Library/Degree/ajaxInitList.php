<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DegreeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DegreeManager.inc.php");
    $degreeManager = DegreeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (dg.degreeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeCode LIKE 
	   "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR studentId LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeAbbr LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';  
	 // $filter = ' AND (dg.degreeName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeCode LIKE 
	 // "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';  
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'degreeName';
    
     
    if ($sortField == "studentId") {
		 $orderBy = " $sortField $sortOrderBy";
    }
	 else {
		 $orderBy = " dg.$sortField $sortOrderBy";
	 }

    ////////////
    
    $totalArray = $degreeManager->getDegreeList($filter);
    $degreeRecordArray = $degreeManager->getDegreeList($filter,$limit,$orderBy);
    $cnt = count($degreeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $degreeRecordArray[$i]['degreeId'] , 'srNo' => ($records+$i+1) ),$degreeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 29/05/09   Time: 11:43
//Updated in $/LeapCC/Library/Degree
//Done bug fixing------ Issues [28-May-09]Build# cc0007
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Degree
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Degree
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:58p
//Updated in $/Leap/Source/Library/Degree
//Added AjaxList Functinality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:25a
//Created in $/Leap/Source/Library/Degree
//Initial checkin
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