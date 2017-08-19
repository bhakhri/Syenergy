<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentProgramFee');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentProgramFeeManager.inc.php");
$programFeeManager = StudentProgramFeeManager::getInstance();

$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)* RECORDS_PER_PAGE;
$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])){
  $filter = ' WHERE (programFeeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
}

$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'programFeeName';
$orderBy = " $sortField $sortOrderBy";

////////////
$totalArray = $programFeeManager->getTotalProgramFees($filter);
$programFeeRecordArray = $programFeeManager->getProgramFeeList($filter,$limit,$orderBy);
$cnt = count($programFeeRecordArray);

for($i=0;$i<$cnt;$i++) {
   $valueArray = array_merge(array('action' => $programFeeRecordArray[$i]['programFeeId'] , 'srNo' => ($records+$i+1) ),$programFeeRecordArray[$i]);
   if(trim($json_val)=='') {
        $json_val = json_encode($valueArray);
   }
   else {
        $json_val .= ','.json_encode($valueArray);
   }
}
echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
?>