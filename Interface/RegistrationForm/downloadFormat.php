<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
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
	header('Content-Disposition: attachment; filename="SectionUploadInstructions.txt"');
	readfile(BL_PATH . '/RegistrationForm/SectionUploadInstructions.txt');

}
elseif($task == 'suf') {
	ob_end_clean();
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header('Content-Disposition: attachment; filename="SectionUploadFormat.xls"');
	// The PDF source is in original.pdf
	readfile(BL_PATH . '/RegistrationForm/SectionUploadFormat.xls');
}
elseif($task == 'mui') {
    ob_end_clean();
    header("Content-Type: application/force-download");
    header("Content-Type: text/html");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename="MarksUploadInstructions.txt"');
    readfile(BL_PATH . '/RegistrationForm/MarksUploadInstructions.txt');

}
elseif($task == 'muf') {
    ob_end_clean();
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename="MarksUploadFormat.xls"');
    // The PDF source is in original.pdf
    readfile(BL_PATH . '/RegistrationForm/MarksUploadFormat.xls');
}
elseif($task == 'meuf') {
    ob_end_clean();
    header("Content-Type: application/force-download");
    header("Content-Type: text/html");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename="MentorshipUploadInstructions.txt"');
    readfile(BL_PATH . '/RegistrationForm/MentorshipUploadInstructions.txt');

}
elseif($task == 'meui') {
    ob_end_clean();
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename="MentorshipUploadFormat.xls"');
    // The PDF source is in original.pdf
    readfile(BL_PATH . '/RegistrationForm/MentorshipUploadFormat.xls');
}
?>