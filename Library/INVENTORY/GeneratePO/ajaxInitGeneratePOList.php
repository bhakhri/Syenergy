<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete
// functionality
// Author : Jaineesh
// Created on : (04 Aug 2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','InventoryGeneratePO');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $poManager = POManager::getInstance();

    /////////////////////////

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
//	echo $limit;
    //////
    /// Search filter /////

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending'){
			$type = 0;
		}
		else if(strtolower(trim($REQUEST_DATA['searchbox']))=='approved'){
			$type = 1;
		}
		else if(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled'){
			$type = 2;
		}
		else{
			$type= -1;
		}

		$filter = ' HAVING (ipm.poNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ipm.poDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%"
		OR status LIKE "%'.add_slashes(trim($type)).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'poNo';
    $orderBy = " $sortField $sortOrderBy";

    ////////////

    //$totalArray = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);
	//$count = count($totalArray);
	$totalCount  = $poManager->getGeneratedPo($filter,'',$orderBy,$sortField);
	$count = count($totalCount);
	$poRecordArray  = $poManager->getGeneratedPo($filter,$limit,$orderBy,$sortField);
	//print_r($poRecordArray); die;
    $cnt = count($poRecordArray);
    for($i=0;$i<$cnt;$i++) {
		$poRecordArray[$i]['poDate'] = UtilityManager::formatDate($poRecordArray[$i]['poDate']);
		$poRecordArray[$i]['status'] = $poStatusArray[$poRecordArray[$i]['status']];
		$actionStr = '<a href="#" title="Cancel"><img src="'.IMG_HTTP_PATH.'/cancelled.gif" alt="Cancelled" onclick="cancelledPO('.$poRecordArray[$i]['poId'].');return false;" border="0"></a>&nbsp;';

        $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$poRecordArray[$i]);

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