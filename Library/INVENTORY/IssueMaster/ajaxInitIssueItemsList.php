<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','RequisitionIssueMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
    $issueManager = IssueManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    /// Search filter /////  

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR irm.approvedOn LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

    
    
	$approvedItemsRecordArray  = $issueManager->getApprovedItemsList($filter,$limit,$orderBy);

    $cnt = count($approvedItemsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$approvedItemsRecordArray[$i]['requisitionDate'] = UtilityManager::formatDate($approvedItemsRecordArray[$i]['requisitionDate']);
		$approvedItemsRecordArray[$i]['approvedOn'] = UtilityManager::formatDate($approvedItemsRecordArray[$i]['approvedOn']);
		
		$actionStr = '<a href="#" title="Approve"><img src="'.IMG_HTTP_PATH.'/tick1.gif" alt="Approve" onclick="editWindow('.$approvedItemsRecordArray[$i]['requisitionId'].',\'EditRequisition\',700,600);return false;" border="0"></a>&nbsp;';

        $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$approvedItemsRecordArray[$i]);

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