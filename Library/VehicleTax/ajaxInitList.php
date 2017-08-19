<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (25.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleTax');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleTaxManager.inc.php");
    $vehicleTaxManager = VehicleTaxManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $vehicleTaxManager->getTotalVehicleTax($filter);
	$count = count($totalArray);
    $vehicleTaxRecordArray = $vehicleTaxManager->getVehicleTaxList($filter,$limit,$orderBy);
    $cnt = count($vehicleTaxRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $vehicleTaxRecordArray[$i]['busNoValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['busNoValidTill']);
		$vehicleTaxRecordArray[$i]['passengerTaxValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['passengerTaxValidTill']);
		$vehicleTaxRecordArray[$i]['roadTaxValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['roadTaxValidTill']);
		$vehicleTaxRecordArray[$i]['pollutionCheckValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['pollutionCheckValidTill']);
		$vehicleTaxRecordArray[$i]['passingValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['passingValidTill']);
        $valueArray = array_merge(array('action' => $vehicleTaxRecordArray[$i]['tyreId'] , 'srNo' => ($records+$i+1) ),$vehicleTaxRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Library/VehicleTax
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Library/VehicleTax
//put DL image in transport staff and changes in modules
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:21p
//Created in $/Leap/Source/Library/VehicleTax
//new ajax files for vehicle tax
//
//
?>