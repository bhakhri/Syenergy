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
    define('MODULE','Vehicle');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$filter = '';

	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	  $searchBox = add_slashes(trim($REQUEST_DATA['searchbox']));
      $filter = " AND bs.busNo LIKE '$searchBox%' OR DATE_FORMAT(bi.insuranceDueDate,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%'  OR DATE_FORMAT(bi.lastInsuranceDate,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR DATE_FORMAT(pvno.passengerTaxValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR 
	  DATE_FORMAT(rtno.roadTaxValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR DATE_FORMAT(pcno.pollutionCheckValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR DATE_FORMAT(pano.passingValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR vt.vehicleType LIKE '$searchBox%'";
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = " ORDER BY $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $vehicleManager->getCountVehicleList($filter);
    $busRecordArray = $vehicleManager->getVehicleList($filter,$limit,$orderBy);
    $cnt = count($busRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        //$busRecordArray[$i]['busId'] = $busRecordArray[$i]['busId'];
        $busRecordArray[$i]['busNo'] = strip_slashes($busRecordArray[$i]['busNo']);
        $busRecordArray[$i]['vehicleType'] = strip_slashes($busRecordArray[$i]['vehicleType']);
        //$busRecordArray[$i]['lastInsuranceDate'] = strip_slashes($busRecordArray[$i]['lastInsuranceDate']);
        $busRecordArray[$i]['insuringCompanyName'] = strip_slashes($busRecordArray[$i]['insuringCompanyName']);
        $busRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($busRecordArray[$i]['lastInsuranceDate']);
        $busRecordArray[$i]['insuranceDueDate'] = UtilityManager::formatDate($busRecordArray[$i]['insuranceDueDate']);   
        $busRecordArray[$i]['passengerTaxValidTill'] = UtilityManager::formatDate($busRecordArray[$i]['passengerTaxValidTill']);   
        $busRecordArray[$i]['roadTaxValidTill'] = UtilityManager::formatDate($busRecordArray[$i]['roadTaxValidTill']);   
        $busRecordArray[$i]['pollutionCheckValidTill'] = UtilityManager::formatDate($busRecordArray[$i]['pollutionCheckValidTill']);
        $busRecordArray[$i]['passingValidTill'] = UtilityManager::formatDate($busRecordArray[$i]['passingValidTill']);   
        $busRecordArray[$i]['isActive'] = $busRecordArray[$i]['isActive']==1?'Yes':'No';
        
        $valueArray = array_merge(array('action' => $busRecordArray[$i]['busId'] , 'srNo' => ($records+$i+1) ),$busRecordArray[$i]);

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
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Library/Vehicle
//fixed bug during self testing
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/12/09    Time: 10:14
//Updated in $/Leap/Source/Library/Vehicle
//checked in files
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/07/09   Time: 12:43p
//Created in $/Leap/Source/Library/Vehicle
//initial file check-in
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Library/Bus
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 10/04/09   Time: 17:35
//Updated in $/SnS/Library/Bus
//Corrected query parameters
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/04/09    Time: 11:10
//Updated in $/SnS/Library/Bus
//Done bug fixing
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:37
//Updated in $/SnS/Library/Bus
//Enhanced bus master module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Library/Bus
//Created Bus Master Module
?>
