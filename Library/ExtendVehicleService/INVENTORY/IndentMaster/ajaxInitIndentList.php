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
    define('MODULE','IndentMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
    $indentManager = IndentManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
	//////
    /// Search filter /////

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	  if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled') {
           $type=1;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='generatedpo') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' HAVING (iim.indentNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR iim.indentStatus LIKE "'.$type.'%" OR iim.indentDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR totalCount = "'.$REQUEST_DATA['searchbox'].'")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'indentNo';
    
	$sortField1 = $sortField;
	if($sortField=='indentNo') {
	   //$sortField1 = "LENGTH(indentNo)+0,indentNo";
	   $orderBy = " LENGTH(indentNo)+0 $sortOrderBy, indentNo $sortOrderBy";
	}
	else {
		$orderBy = " $sortField1 $sortOrderBy";
	}

    ////////////
    
    //$totalArray = $requisitionManager->getRequisitionList($filter,'',$orderBy);
	//$count = count($totalArray);
	
	$indentRecordArray  = $indentManager->getIndentList($filter,'',$orderBy);
	$count = count($indentRecordArray);

	$indentRecordArray  = $indentManager->getIndentList($filter,$limit,$orderBy);
    $cnt = count($indentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$actionStr = "";
		$indentRecordArray[$i]['indentDate'] = UtilityManager::formatDate($indentRecordArray[$i]['indentDate']);  
		$indentRecordArray[$i]['indentStatus'] = $indentStatusArray[$indentRecordArray[$i]['indentStatus']];
		$indentRecordArray[$i]['totalCount'] = "<a href='#' title='Show Detail' onclick='printIndentReport(".$indentRecordArray[$i]['indentId'].")'>".$indentRecordArray[$i]['totalCount']."</a>";
		if($indentRecordArray[$i]['indentStatus'] == "Pending") {
			$actionStr = '<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" 
			onclick="editWindow('.$indentRecordArray[$i]['indentId'].',\'EditIndent\',700,600);return false;" border="0"></a>&nbsp;';
		}
		$actionStr .= '<a href="#" title="Cancel"><img src="'.IMG_HTTP_PATH.'/cancelled.gif" border="0" alt="Cancelled" width = "18px" height = "18px" onclick="cancelledIndent('.$indentRecordArray[$i]['indentId'].');return false;"></a>&nbsp;';
        $valueArray = array_merge(array('action1' => $actionStr , 'srNo' => ($records+$i+1) ),$indentRecordArray[$i]);

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