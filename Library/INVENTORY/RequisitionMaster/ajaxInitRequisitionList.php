<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Jaineesh
// Created on : (02 Aug 2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RequisitionMaster');
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
	  if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='approved') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled') {
           $type=3;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='issued') {
           $type=4;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelledbyhod') {
           $type=5;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelledbystore') {
           $type=6;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='incomplete') {
           $type=7;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionStatus LIKE "'.$type.'%"  OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR totalCount = "'.$REQUEST_DATA['searchbox'].'" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";
 
    $totalArray = $requisitionManager->getRequisitionList($filter,'',$orderBy);
	$count = count($totalArray);
	$requisitionRecordArray  = $requisitionManager->getRequisitionList($filter,$limit,$orderBy);

    $cnt = count($requisitionRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$requisitionRecordArray[$i]['requisitionDate'] = UtilityManager::formatDate($requisitionRecordArray[$i]['requisitionDate']);  
		$requisitionRecordArray[$i]['requisitionStatus'] = $requisitionStatusArray[$requisitionRecordArray[$i]['requisitionStatus']];
		$requisitionRecordArray[$i]['totalCount'] = "<a href='#' title='Show Detail' onclick='printRequisitionReport(".$requisitionRecordArray[$i]['requisitionId'].")'>".$requisitionRecordArray[$i]['totalCount']."</a>";
		$actionStr = '<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editWindow('.$requisitionRecordArray[$i]['requisitionId'].',\'EditRequisition\',700,600);return false;" border="0"></a>&nbsp;
         <a href="#" title="Cancel"><img src="'.IMG_HTTP_PATH.'/cancelled.gif" border="0" alt="Cancelled" width = "18px" height = "18px" onclick="cancelledRequisition('.$requisitionRecordArray[$i]['requisitionId'].');return false;"></a>&nbsp;';

        $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$requisitionRecordArray[$i]);

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