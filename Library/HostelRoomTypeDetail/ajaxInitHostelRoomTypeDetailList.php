<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HostelRoomTypeDetail');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HostelRoomTypeDetailManager.inc.php");
    $hostelRoomTypeDetailManager = HostelRoomTypeDetailManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
       }
	   else {
		   $type=-1;
	   }
       

       $filter = ' AND (h.hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrt.roomType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.Capacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.noOfBeds LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.attachedBath LIKE "%'.$type.'%" OR hrtd.airConditioned LIKE "%'.$type.'%" OR hrtd.internetFacility LIKE "%'.$type.'%" OR hrtd.noOfFans LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.noOfLights LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $hostelRoomTypeDetailManager->getTotalHostelRoomTypeDetail($filter);
    $hostelRoomDetailRecordArray = $hostelRoomTypeDetailManager->getHostelRoomTypeDetailList($filter,$limit,$orderBy);
    $cnt = count($hostelRoomDetailRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add hostelRoomId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $hostelRoomDetailRecordArray[$i]['roomTypeInfoId'] , 'srNo' => ($records+$i+1) ),$hostelRoomDetailRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitHostelRoomTypeDetailList.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:18p
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//fixed bugs Issues Build cc0001.doc
//(Nos.991,992,993,994,995,996,997,998,999,1000)
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//new ajax files uploaded for hostel room type detail add, delete, edit &
//list
//
//
?>
