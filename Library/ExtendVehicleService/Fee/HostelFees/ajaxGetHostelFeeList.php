<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student marks
// Author : Nishu Bindal
// Created on : (18.02.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','HostelFeeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Fee/HostelFeeManager.inc.php");
    $HostelFeeManager = HostelFeeManager::getInstance();
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    
    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';

	$sortField = $REQUEST_DATA['sortField'];
	if($sortFiled == ''){
		$sortField ="hostelName";
	}
	
	$orderBy = " $sortField $sortOrderBy";
	$hostelId = $REQUEST_DATA['hostelId'];
	$roomTypeId = $REQUEST_DATA['roomTypeId'];
	$classId = $REQUEST_DATA['classId'];
	
	if(($hostelId == '' || $roomTypeId == '') || ($classId == '')  ){
		echo "Required Parameter is Missing !!!";
		die;
	}
	
	
	$filter = "AND	hr.hostelId IN ($hostelId)";
	if($roomTypeId !='all'){
		$filter .=" AND	hr.hostelRoomTypeId = '$roomTypeId'";
	}
	
    $totalArray = $HostelFeeManager->getHostelTotalRooms($filter);
 
    $hostelRoomRecordArray=$HostelFeeManager->getHostelRoomList($filter,$classId,$limit,$orderBy);
    
    $cnt = count($hostelRoomRecordArray);  //count of student records

    for($i=0;$i<$cnt;$i++){
         $valueArray = array_merge(array("srNo" => ($records+$i+1),"roomRent"=>"<input type='textbox' class='inputbox' onblur='checkValue(this.value,this.id)' style='width:100px;text-align:right;' name=roomRentAmount id='roomRentAmount$i' value=".$hostelRoomRecordArray[$i]['amount']."><input type='hidden' id='data$i' value=".$hostelRoomRecordArray[$i]['hostelId'].'-'.$hostelRoomRecordArray[$i]['hostelRoomTypeId']." name='data'>"
         ),$hostelRoomRecordArray[$i]);
 
       if(trim($json_val)==''){
            $json_val = json_encode($valueArray);
       }
       else{
            $json_val .= ','.json_encode($valueArray);
       }
    }
    //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
