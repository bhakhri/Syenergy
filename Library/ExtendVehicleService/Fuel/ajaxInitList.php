<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FuelMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FuelManager.inc.php");
    $tranportManager = FuelManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND  ( trs.name LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.busNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.lastMilege LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.currentMilege LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.litres LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.amount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';
    	$sortField1 = $sortField;
	if($sortField=='currentMilege') {
	   //$sortField1 = "LENGTH(indentNo)+0,indentNo";
	   $orderBy = " LENGTH(currentMilege)+0 $sortOrderBy, currentMilege $sortOrderBy";
	}
	else if($sortField=='lastMilege') {
		 $orderBy = " LENGTH(lastMilege)+0 $sortOrderBy, lastMilege $sortOrderBy";
	}
	else {
		$orderBy = " $sortField1 $sortOrderBy";
	}
	
    ////////////
    
    $totalArray           = $tranportManager->getTotalFuel($filter);
    $transportRecordArray = $tranportManager->getFuelList($filter,$limit,$orderBy);
    $cnt = count($transportRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $transportRecordArray[$i]['dated'] = UtilityManager::formatDate($transportRecordArray[$i]['dated']);
       
        $valueArray = array_merge(array('action' => $transportRecordArray[$i]['fuelId'] , 'srNo' => ($records+$i+1) ),$transportRecordArray[$i]);

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
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/08/09    Time: 15:46
//Updated in $/Leap/Source/Library/Fuel
//Done bug fixing.
//bug ids---
//0000817 to 0000821
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Library/Fuel
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/Fuel
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/04/09   Time: 10:37
//Updated in $/SnS/Library/Fuel
//Done bug fixing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Library/Fuel
//Created Fuel Master
?>