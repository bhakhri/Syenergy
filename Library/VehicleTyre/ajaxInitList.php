<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (25.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleTyreMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
    $vehicleTyreManager = VehicleTyreManager::getInstance();

    /////////////////////////
	$vehicleTypeId = $REQUEST_DATA['vehicleType'];
	$vehicleNo = $REQUEST_DATA['vehicleNo'];
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (tm.tyreNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tm.manufacturer LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tm.modelNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tm.isActive LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR th.busReadingOnInstallation LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tyreNumber';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    $filter .= "AND b.vehicleTypeId = $vehicleTypeId AND b.busId = $vehicleNo";
    //$totalArray = $vehicleTyreManager->getTotalVehicleTyre($filter);
    $vehicleTyreRecordArray = $vehicleTyreManager->getVehicleTyreList($filter,$limit,$orderBy);
    $cnt = count($vehicleTyreRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $vehicleTyreRecordArray[$i]['purchaseDate'] = UtilityManager::formatDate($vehicleTyreRecordArray[$i]['purchaseDate']);
		$actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit2.gif" border="0" alt="Edit" onclick="editWindow('.$vehicleTyreRecordArray[$i]['tyreId'].');return false;"></a>
					<a href="#" title="Purchase"><img src="'.IMG_HTTP_PATH.'/tyre_add.png" border="0" onClick="editAddWindow('.$vehicleTyreRecordArray[$i]['tyreId'].');"/></a>
					<a href="#" title="Replace"><img src="'.IMG_HTTP_PATH.'/tyre_delete.png" border="0" onClick="addStockWindow('.$vehicleTyreRecordArray[$i]['tyreId'].');"/></a>';

		$valueArray = array_merge(
                              array(
                                    'action1' => $actionStr,
                                    'srNo' => ($records+$i+1)
                                   ),
                                    $vehicleTyreRecordArray[$i]);

        //$valueArray = array_merge(array('action' => $vehicleTyreRecordArray[$i]['tyreId'] , 'srNo' => ($records+$i+1) ),$vehicleTyreRecordArray[$i]);

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
//*****************  Version 7  *****************
//User: Jaineesh     Date: 2/03/10    Time: 7:00p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug nos. 0002555, 0002722
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug in fleet management
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Library/VehicleTyre
//bug fixed for fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug for fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/01/09   Time: 6:59p
//Updated in $/Leap/Source/Library/VehicleTyre
//changes in interface of vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/25/09   Time: 3:31p
//Created in $/Leap/Source/Library/VehicleTyre
//new ajax files for vehicle tyre
//
//
?>
