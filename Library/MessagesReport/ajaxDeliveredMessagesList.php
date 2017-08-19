<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div

// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MessagesList');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::headerNoCache();

require_once($FE . "/Library/HtmlFunctions.inc.php");
$htmlManager  = HtmlFunctions::getInstance();
$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)* RECORDS_PER_PAGE;
$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';

$orderBy = " ORDER BY $sortField $sortOrderBy";

require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
$smsdetailManager  = SMSDetailManager::getInstance();

$messageId = $REQUEST_DATA['messageId'];
$dlvrMessagesIdsList='';
if(trim($messageId) != '') {
	$cnt = 0;
	$undeliveredMsgsArray = $smsdetailManager->getUndeliveredMessagesIds($messageId);
	if ($undeliveredMsgsArray[0]['receiverIds'] == '') { # MESSAGE IS DELIVERED TO ALL
		$sendMessagesArray = $smsdetailManager->sendMessagesIds($messageId);
		$sendMessagesList = substr($sendMessagesArray[0]['receiverIds'],1,-1);
		$sendMessagesArray = explode("~",$sendMessagesList);
		$dlvrMessagesIdsList = implode(",",$sendMessagesArray);
		$deleveredMessagesArray = $smsdetailManager->getdeliveredMessages($messageId,$dlvrMessagesIdsList,$orderBy,$limit);
		$cnt = count($deleveredMessagesArray);
	}
	else {
		$sendMessagesArray = $smsdetailManager->sendMessagesIds($messageId);
		$sendMessagesList = substr($sendMessagesArray[0]['receiverIds'],1,-1);
		if ($undeliveredMsgsArray[0]['receiverIds'] != $sendMessagesList) { # MESSAGE RECEIPIENTS LIST IS NOT SAME AS MESSAGE FAILURE LIST
			$undeliveredMessagesArray = explode(",",$undeliveredMsgsArray[0]['receiverIds']);
			$sendMessagesArray = explode("~",$sendMessagesList);
			$messagesDeliveredArray = array_values(array_diff($sendMessagesArray,$undeliveredMessagesArray));
			if(is_array($messagesDeliveredArray) && count($messagesDeliveredArray)>0 ) {
				$dlvrMessagesIdsList = implode(",",$messagesDeliveredArray);
				$deleveredMessagesArray = $smsdetailManager->getdeliveredMessages($messageId,$dlvrMessagesIdsList,$orderBy,$limit);
				$cnt = count($deleveredMessagesArray);
			}
		}
	}
	for($i=0;$i<$cnt;$i++){
		$valueArray = array_merge($deleveredMessagesArray[$i], array('srNo' => ($records+$i+1) ));
		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);
		}
	}
	echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
}
else {
	echo INVALID_MESSAGE_ID;
}

?>