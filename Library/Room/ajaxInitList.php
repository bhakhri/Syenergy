<?php
//-------------------------------------------------------
// Purpose: To store the records of room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoomsMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoomManager.inc.php");
    $roomManager = RoomManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=="laboratory") {
				   $type="Laboratory";
		   }
		   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=="theory") {
			   $type="Theory";
		   }
        else{
             $type=-1;
		}
		   
       $filter = ' AND (roomName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR roomAbbreviation LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR capacity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR roomType LIKE "%'.$type.'%" OR blockName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR buildingName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR examCapacity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $roomManager->getTotalRoom($filter);
    $roomRecordArray = $roomManager->getRoomList($filter,$limit,$orderBy);
    $cnt = count($roomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add roomId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $roomRecordArray[$i]['roomId'] , 'srNo' => ($records+$i+1) ),$roomRecordArray[$i]);

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
//User: Jaineesh     Date: 10/20/09   Time: 1:02p
//Updated in $/LeapCC/Library/Room
//fixed bug nos. 0001811, 0001800, 0001798, 0001795, 0001793, 0001782,
//0001800, 0001813
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/03/09    Time: 1:39p
//Updated in $/LeapCC/Library/Room
//Gurkeerat: resolved issue 1356
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/26/09    Time: 6:10p
//Updated in $/LeapCC/Library/Room
//fixed bugs No.5,6 bugs-report.doc dated 26.05.09
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Room
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Room
//define access module
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:22p
//Updated in $/Leap/Source/Library/Room
//modified in sql query
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/12/08    Time: 11:38a
//Updated in $/Leap/Source/Library/Room
//modified in block with blockname through blockid
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/08    Time: 8:27p
//Created in $/Leap/Source/Library/Room
//add function for search the data from room table

  
?>
