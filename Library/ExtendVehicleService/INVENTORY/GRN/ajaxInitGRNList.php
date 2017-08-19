<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Jaineesh
// Created on : (04 Aug 2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','InventoryGRN');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (ip.partyCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR igm.billNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR igm.billDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'partyCode';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    //$totalArray = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);
	//$count = count($totalArray);
	$grnRecordCountArray  = $grnManager->getGRNCount($filter,$orderBy);
	$count = count($grnRecordCountArray);
	$grnRecordArray  = $grnManager->getGRNList($filter,$limit,$orderBy);

  $cnt = count($grnRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$grnRecordArray[$i]['billDate'] = UtilityManager::formatDate($grnRecordArray[$i]['billDate']);  
		
		$actionStr = '<a href="#" title="Cancel"><img src="'.IMG_HTTP_PATH.'/cancelled.gif" alt="Cancelled" onclick="cancelledGRN('.$grnRecordArray[$i]['grnId'].',\'EditGRN\',700,600);return false;" border="0"></a>&nbsp;';

        $valueArray = array_merge(array('action1' => $actionStr, 'srNo' => ($records+$i+1) ),$grnRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
	
  echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';
    
// for VSS
// $History: $
//
?>