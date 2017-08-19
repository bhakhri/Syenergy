<?php
//-------------------------------------------------------
// Purpose: To store the records of designation in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','DesignationMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DesignationManager.inc.php");
    $designationManager = DesignationManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (designationName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR designationCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR employeeCount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'designationName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $designationManager->getTotalDesignation($filter);
    $designationRecordArray = $designationManager->getDesignationList($filter,$limit,$orderBy);
    $cnt = count($designationRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add designationId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $designationRecordArray[$i]['designationId'] , 'srNo' => ($records+$i+1) ),$designationRecordArray[$i]);

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
//User: Gurkeerat    Date: 9/29/09    Time: 12:51p
//Updated in $/LeapCC/Library/Designation
//resolved issue 1628
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Designation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:34p
//Updated in $/Leap/Source/Library/Designation
//define access values
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Designation
//modified in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/30/08    Time: 9:42a
//Updated in $/Leap/Source/Library/Designation
//Give the list of designation data through ajax
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:44p
//Created in $/Leap/Source/Library/FeeCycle
//ajax functions used for delete, edit, update, search, add 
  
?>