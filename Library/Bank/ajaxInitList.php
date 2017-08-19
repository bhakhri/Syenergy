<?php
//-------------------------------------------------------
// Purpose: To store the records of Bank in array from the database, pagination and search, delete
// functionality
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BankMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BankManager.inc.php");
    $bankManager = BankManager::getInstance();

    /////////////////////////


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE bankName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bankAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';

     $orderBy = " $sortField $sortOrderBy";


    $totalArray = $bankManager->getTotalBank($filter);
    $bankRecordArray = $bankManager->getBankList($filter,$limit,$orderBy);
    $cnt = count($bankRecordArray);

    for($i=0;$i<$cnt;$i++) {
        // add bankId in actionId to populate edit/delete icons in User Interface
        $actionStr1 = "<input type='image' title ='Add' src=".IMG_HTTP_PATH."/add.gif
                        align='center' onClick='addBankBranchWindow(".$bankRecordArray[$i]['bankId']."); return false;' />&nbsp";
        $actionStr2 = "<input type='image' title ='Edit' src=".IMG_HTTP_PATH."/edit.gif
                        align='center' onClick='listBankBranchWindow(".$bankRecordArray[$i]['bankId']."); return false;' />&nbsp";
	if($bankRecordArray[$i]['bankAddress'] == ''){
		$bankRecordArray[$i]['bankAddress'] = '---';
	}
        $valueArray = array_merge(array('addBranch' => $actionStr1, 'viewBranch' => $actionStr2,'action' =>  $bankRecordArray[$i]['bankId'] , 'srNo' => ($records+$i+1) ),$bankRecordArray[$i]);

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
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Library/Bank
//Merged Bank & BankBranch module in single module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Bank
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:56a
//Updated in $/Leap/Source/Library/Bank
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master


?>
