<?php
//-------------------------------------------------------
//  This File contains logic for ledgers
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Ledger');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . '/Accounts/LedgerManager.inc.php');
    $ledgerManager = LedgerManager::getInstance();


    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = " AND (led.ledgerName LIKE '".$REQUEST_DATA['searchbox']."%' OR grp.groupName LIKE '".$REQUEST_DATA['searchbox']."%')";
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'ledgerName';
    
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $ledgerManager->getTotalLedgers($filter);
    $ledgerRecordArray = $ledgerManager->getLedgersList($filter,$limit,$orderBy);
	
    $cnt = count($ledgerRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add bankId in actionId to populate edit/delete icons in User Interface
		$opDrAmount = floatval($ledgerRecordArray[$i]['opDrAmount']);
		$opCrAmount = floatval($ledgerRecordArray[$i]['opCrAmount']);

		$ledgerRecordArray[$i]['openingBalance'] = 0;
		if ($opDrAmount > 0) {
			$ledgerRecordArray[$i]['openingBalance'] = $opDrAmount . ' Dr';
		}
		elseif ($opCrAmount > 0) {
			$ledgerRecordArray[$i]['openingBalance'] = $opCrAmount . ' Cr';
		}
        $valueArray = array_merge(array('action' => $ledgerRecordArray[$i]['ledgerId'] , 'srNo' => ($records+$i+1) ),$ledgerRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/Ledger
//file added
//



?>
