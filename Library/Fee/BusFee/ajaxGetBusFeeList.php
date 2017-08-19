<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of Bus Fees
// Author : Nishu Bindal
// Created on : (23.02.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleFeeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Fee/BusFeeManager.inc.php");
    $BusFeeManager = BusFeeManager::getInstance();
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    
    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';

	$sortField = $REQUEST_DATA['sortField'];
	if($sortFiled == ''){
		$sortField ="routeCode";
	}
	
	$orderBy = " $sortField $sortOrderBy";
	$selectedRouteId = $REQUEST_DATA['selectedRouteId'];
	$busStopHeadId = $REQUEST_DATA['busStopHeadId'];
	$classId = $REQUEST_DATA['classId'];
	
	if(($selectedRouteId == '' || $busStopHeadId == '') || ($classId == '')  ){
		echo "Required Parameter is Missing !!!";
		die;
	}
	
	
	$filter = "AND	brsm.busRouteId IN ($selectedRouteId)";
	if($busStopHeadId !='all'){
		$filter .=" AND	bs.busStopCityId = '$busStopHeadId'";
	}
	
    $totalArray = $BusFeeManager->getTotalBusStopHeads($filter);
 
    $busFeeRecordArray=$BusFeeManager->getBusStopHeadsList($filter,$classId,$limit,$orderBy);
    
    $cnt = count($busFeeRecordArray);  //count of student records

    for($i=0;$i<$cnt;$i++){
         $valueArray = array_merge(array("srNo" => ($records+$i+1),"busFeeTextBox"=>"<input type='textbox' onblur='checkValue(this.value,this.id)' class='inputbox'  style='width:100px;text-align:right;' name=busFee id='busFee$i' value=".$busFeeRecordArray[$i]['amount']."><input type='hidden' id='data$i' value=".$busFeeRecordArray[$i]['busRouteId'].'-'.$busFeeRecordArray[$i]['busStopCityId']." name='data'>"
         ),$busFeeRecordArray[$i]);
 
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
