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
    define('MODULE','InsuranceVehicle');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
    $insuranceVehicleManager = InsuranceVehicleManager::getInstance();

    /////////////////////////
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    /// Search filter /////  
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='cash') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cheque') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='draft') {
           $type=3;
       }
	   else {
		   $type=-1;
	   }

       $filter = ' AND (b.busNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ic.insuringCompanyName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bi.valueInsured LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bi.insurancePremium LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bi.ncb LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bi.paymentMode LIKE "%'.$type.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $insuranceVehicleManager->getTotalInsuranceVehicle($filter);
    $insuranceVehicleRecordArray = $insuranceVehicleManager->getInsuranceVehicleList($filter,$limit,$orderBy);
    $cnt = count($insuranceVehicleRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $insuranceVehicleRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($insuranceVehicleRecordArray[$i]['lastInsuranceDate']);
        $valueArray = array_merge(array('action' => $insuranceVehicleRecordArray[$i]['insuranceId'] , 'srNo' => ($records+$i+1) ),$insuranceVehicleRecordArray[$i]);

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
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Library/InsuranceVehicle
//fixed bug in fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/InsuranceVehicle
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:16p
//Created in $/Leap/Source/Library/InsuranceVehicle
//new ajax files for vehicle insurance
//
//
?>