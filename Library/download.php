<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

if (!isset($REQUEST_DATA['t'])) {

}
else {
	$task = $REQUEST_DATA['t'];
	
	if ($task == 'fm') {
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="Faculty Manual.pdf"');
		readfile(STORAGE_PATH . "/Help/PDF/Manual/FacultyManual.pdf");
	}
	else if ($task == 'fpr') {
		ob_end_clean();
		$fileName = LIB_PATH. "/Templates/Xml/".$REQUEST_DATA['u'].".dbf";
		$newFileName = str_replace(' ','',$REQUEST_DATA['u']);
		$newFileName = strtoupper($newFileName);
		$newFileName .= '.dbf';
		header("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename="'.$newFileName.'"');
		readfile($fileName);
	}
	else if ($task == 'dbf') {
		ob_end_clean();
		$fn = $_GET['fn'];
		$newFileName =  $fn;
		header("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename="'.$newFileName.'"');
		readfile(STORAGE_PATH . "/Broadcast/$fn");
	}
    else if ($task == 'tallyXml ') {
    	ob_end_clean();
        $fileName = LIB_PATH. "/Templates/Xml/feePaymentDetail.xml";
		header("Content-Type: application/force-download");
        header('Content-Disposition: attachment; filename="feePaymentDetail.xml"');
        readfile($fileName);
    }
}
?>