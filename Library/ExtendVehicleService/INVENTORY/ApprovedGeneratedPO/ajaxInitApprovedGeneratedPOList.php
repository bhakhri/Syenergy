<?php
//-------------------------------------------------------
// Purpose: To Generate the List Of Approved Generated Purchase Orders(PO).
// Created on : (26 Nov 2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ApproveGeneratedPO');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $poManager = POManager::getInstance();


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (ipm.poNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ipm.poDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'poNo';

    $orderBy = " $sortField $sortOrderBy";
	$poRecordCountArray  = $poManager->countUnapprovedPOList($filter,'',$orderBy);
	$count= count($poRecordCountArray);
	$poRecordArray  = $poManager->getUnapprovedPOList($filter,$limit,$orderBy);
    $cnt = count($poRecordArray);

    for($i=0;$i<$cnt;$i++) {
		$poRecordArray[$i]['poDate'] = UtilityManager::formatDate($poRecordArray[$i]['poDate']);
		$actionStr = '<a href="#" title="Approve"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Approve" onclick="editGeneratedPO('.$poRecordArray[$i]['poId'].',\'EditGeneratedPO\',700,600);return false;" ></a>&nbsp;<a href="#" title="Cancel"><img src="'.IMG_HTTP_PATH.'/cancelled.gif" style="height:15px;" alt="Cancelled" onclick="cancellGeneratedPO('.$poRecordArray[$i]['poId'].',0,\'EditGeneratePO\',700,700);return false;" border="0"></a>&nbsp;';

        $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$poRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

  echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';

?>