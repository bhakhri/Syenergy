<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();

$periodSlotId = $REQUEST_DATA['periodSlotId'];
$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
$classId = $REQUEST_DATA['studentClass'];
$totalRecords = count($REQUEST_DATA['teacherId']);

require_once(MODEL_PATH . "/TimeTableConflictManager.inc.php");
$timeTableConflictManager = TimeTableConflictManager::getInstance();

$timeTableConflictManager->getNewPeriodArray();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$timeTableConflictManager->setProcess('show conflicts');
$timeTableConflictManager->setProcessSubject('show subject');

$noConflictFound = $timeTableConflictManager->checkConflict();
if ($noConflictFound === true) {
	echo '<br>'.NO_CONFLICTS_FOUND;
}
else {
	echo $noConflictFound;
}

?>