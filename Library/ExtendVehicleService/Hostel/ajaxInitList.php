<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel in array from the database, pagination and search, delete 
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
	define('MODULE','HostelMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HostelManager.inc.php");
    $hostelManager = HostelManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='girls') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='boys') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='mixed') {
           $type=3;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='guest house') {
           $type=4;
       }
	   else {
		   $type=-1;
	   }
       $filter = ' WHERE (hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR wardenName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR wardenContactNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR totalCapacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"
       OR floorTotal LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomTotal LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelType LIKE "%'.$type.'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $hostelManager->getTotalHostel($filter);
    $hostelRecordArray = $hostelManager->getHostelList($filter,$limit,$orderBy);
    $cnt = count($hostelRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add hostelId in actionId to populate edit/delete icons in User Interface
        $hostelRecordArray[$i]['hostelType']=$hostelTypeArr[$hostelRecordArray[$i]['hostelType']];
        $valueArray = array_merge(array('action' => $hostelRecordArray[$i]['hostelId'] , 'srNo' => ($records+$i+1) ),$hostelRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
	//print_r($valueArray);
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/27/09    Time: 11:17a
//Updated in $/LeapCC/Library/Hostel
//fixed bug no.0000621
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Library/Hostel
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/08/09    Time: 1:07p
//Updated in $/LeapCC/Library/Hostel
//fixed bugs Issues BuildCC# cc0001.doc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:10a
//Created in $/LeapCC/Library/Hostel
//ajax files include add, delete, edit & list functions made by Jaineesh
//& modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 6  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Library/Hostel
//added new fields (floorTotal,hostelType,totalCapacity) 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:43p
//Updated in $/Leap/Source/Library/Hostel
//add define access in module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:30p
//Updated in $/Leap/Source/Library/Hostel
//modification in indentation
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:58p
//Created in $/Leap/Source/Library/Hostel
//add new ajax functions 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
?>