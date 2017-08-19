<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
//
// Author : Parveen Sharma
// Created on : (29.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
define('MODULE','MessagesList');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::headerNoCache();

require_once($FE . "/Library/HtmlFunctions.inc.php");
$htmlManager  = HtmlFunctions::getInstance();
// to limit records per page    
$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)*10000;
$limit      = ' LIMIT '.$records.',10000';
    //////

    /// Search filter /////  
$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';

$orderBy = " ORDER BY $sortField $sortOrderBy";
$messageId = $REQUEST_DATA['messageId'];
if(trim($messageId) != '') {
    $smsdetailManager  = SMSDetailManager::getInstance();
	$detailsArray = $smsdetailManager->getUndeliveredMessages($messageId,$orderBy,''); 
	//print_r($detailsArray);  die;
	$cnt=count($detailsArray);
	for($i=0;$i<$cnt;$i++){
		$valueArray = array_merge($detailsArray[$i], array('srNo' => ($records+$i+1) ));
		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);           
		}
	}                                   
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
}

?>