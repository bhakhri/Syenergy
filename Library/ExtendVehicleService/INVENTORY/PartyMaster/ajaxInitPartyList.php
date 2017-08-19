<?php
//-------------------------------------------------------
// Purpose: To store the records of item category in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;   
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PartyMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/PartyManager.inc.php");
    $partyManager = PartyManager::getInstance();

    /////////////////////////

	function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,12):$str);

	if(strlen($ret) > $maxlength){
		$ret=substr($ret,0,$maxlength).$rep; 
	}
	return $ret;
	}
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (partyName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyAddress LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyPhones LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyFax LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'partyName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $partyManager->getTotalParty($filter);

    $partyRecordArray = $partyManager->getPartyList($filter, $orderBy, $limit);
    $cnt = count($partyRecordArray);
   
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		if($partyRecordArray[$i]['partyAddress'] == ''){
			$partyRecordArray[$i]['partyAddress'] = NOT_APPLICABLE_STRING;
		}
		if($partyRecordArray[$i]['partyPhones'] == ''){
			$partyRecordArray[$i]['partyPhones'] = NOT_APPLICABLE_STRING;
		}
		if($partyRecordArray[$i]['partyFax'] == ''){
			$partyRecordArray[$i]['partyFax'] =  NOT_APPLICABLE_STRING;
		}


		
        $valueArray = array_merge(array('action' => $partyRecordArray[$i]['partyId'] , 'srNo' => ($records+$i+1) ),$partyRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
	 
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
?>