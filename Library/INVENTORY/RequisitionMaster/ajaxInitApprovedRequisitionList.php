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
    define('MODULE','ApprovedRequisitionMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
    $requisitionManager = RequisitionManager::getInstance();

    /////////////////////////

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime(trim($REQUEST_DATA['searchbox']))).'%")';
	}
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';

    $orderBy = " $sortField $sortOrderBy";

	$requisitionApprovedRecordCountArray  = $requisitionManager->getApprovedRequisitionCount($filter,$orderBy);
	$count = count($requisitionApprovedRecordCountArray);
	$requisitionApprovedRecordArray  = $requisitionManager->getApprovedRequisitionList($filter,$limit,$orderBy);

    $cnt = count($requisitionApprovedRecordArray);

    for($i=0;$i<$cnt;$i++) {
		$requisitionApprovedRecordArray[$i]['requisitionDate'] = UtilityManager::formatDate($requisitionApprovedRecordArray[$i]['requisitionDate']);
	
		$actionStr = '<a href="#" title="Approve"><input type="image" src="'.IMG_HTTP_PATH.'/approve.gif" alt="Approve" onclick="editWindow('.$requisitionApprovedRecordArray[$i]['requisitionId'].',\'EditApprovedRequisition\',700,600);return false;" border="0"></a>&nbsp;';
	
	        $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$requisitionApprovedRecordArray[$i]);

       	if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

  echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';

?>
