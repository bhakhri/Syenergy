<?php
//---------------------------------------------------------------------------------
//Purpose: This file stores the current List of the notifications from the database
//Author: Kavish Manjkhola
//Created On: 31/04/2011
//Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Notifications');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/NoticeManager.inc.php");
$noticeManager = NoticeManager::getInstance();

$timeFormat = '12';
$actionStr = '';


// For this module by default the Limit is set to 30. To configure further click on 'OTHERS' tab in 'Setup » Config Master' module.
$endLimit = $sessionHandler->getSessionVariable('RECORD_LIMIT_FOR_NOTIFICATION');
if($endLimit=='') {
  $endLimit=100;  
}
$limit = ' LIMIT 0,'.$endLimit;


/// Search filter /////  
if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
   $filter = ' WHERE (message LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR publishDateTime LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
}
$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'publishDateTime';

$orderBy = " $sortField $sortOrderBy";

$notificationsArray=array();
$notificationsArray=$noticeManager->getNotificationsCount();
$count = count($notificationsArray);

$notificationsArray=$noticeManager->getNotificationsList($filter,$orderBy,$limit);
$cnt = count($notificationsArray);

for($i=0;$i<$cnt;$i++) {
	$notificationsArray[$i]['publishDateTime'] = UtilityManager::formatDate($notificationsArray[$i]['publishDateTime'],true,$timeFormat);
	$notificationsArray[$i]['viewDateTime'] = UtilityManager::formatDate($notificationsArray[$i]['viewDateTime'],true,$timeFormat);
	$notificationsArray[$i]['message'] = $notificationsArray[$i]['message'];
	//$actionStr = '<a href="'.UI_HTTP_PATH.'/vehicleInsurance.php">Click Here For Details</a>';

	$actionStr = '<a href="'.UI_HTTP_PATH.'/vehicleInsurance.php"><input type="image" src="'.IMG_HTTP_PATH.'/click_here_for_details.gif" alt="Details" title="Details"
	return false;" border="0"></a>&nbsp;';

	$valueArray = array_merge(array('action1' => $actionStr, 'srNo' => ($records+$i+1) ),$notificationsArray[$i]);
	if(trim($json_val)=='') {
		$json_val = json_encode($valueArray);
	}
	else {
		$json_val .= ','.json_encode($valueArray);           
	}
}
echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';
?>