<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete
// functionality
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleInsuranceMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $vehicleInsuranceManager = VehicleInsuranceManager::getInstance();

    /////////////////////////


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
         $filter = ' WHERE (insuringCompanyName  LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR insuringCompanyDetails LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'insuringCompanyName';

     $orderBy = " $sortField $sortOrderBy";

    ////////////

    $totalArray = $vehicleInsuranceManager->getTotalVehicleInsurance($filter);
    $vehicleInsuranceRecordArray = $vehicleInsuranceManager->getVehicleInsuranceList($filter,$limit,$orderBy);
    $cnt = count($vehicleInsuranceRecordArray);

    for($i=0;$i<$cnt;$i++) {
		if ($vehicleInsuranceRecordArray[0]['insuringCompanyDetails'] == '') {
			$vehicleInsuranceRecordArray[0]['insuringCompanyDetails'] = NOT_APPLICABLE_STRING;
		}
        if($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']=='') {
          $vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']= NOT_APPLICABLE_STRING;
        }
        
        if(strlen($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails'])>70) {
           $vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']=substr($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails'],1,70)."..."; 
        }
        
        $valueArray = array_merge(array('action' => $vehicleInsuranceRecordArray[$i]['insuringCompanyId'] , 
                                        'srNo' => ($records+$i+1) ),$vehicleInsuranceRecordArray[$i]);

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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
//
?>