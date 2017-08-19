<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadStudentGroup');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
$task = $REQUEST_DATA['t'];
if (!isset($task) or empty($task)) {
	die;
}
elseif($task == 'sui') {
	
	ob_end_clean();
	header("Content-Type: application/force-download");
	header("Content-Type: text/html");
	header("Content-Type: application/download");
	header('Content-Disposition: attachment; filename="StudentGroupUploadInstructions.txt"');
	readfile(BL_PATH . '/StudentGroupUploadInstructions.txt');

}
elseif($task == 'suf') {
	ob_end_clean();
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header('Content-Disposition: attachment; filename="StudentGroupUploadFormat.xls"');
	// The PDF source is in original.pdf
	readfile(BL_PATH . '/StudentGroupUploadFormat.xls');

}
?>