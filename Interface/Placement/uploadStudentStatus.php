<?php 
//-------------------------------------------------------
// This File outputs the TestType report to the Printer
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementUploadStudentDetail');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Placement Uploaded Student Status CSV</title>
</head>
<body>
<?php 
$mode=trim($REQUEST_DATA['mode']);
if($mode!=0 and $mode!=1){
    die(ACCESS_DENIED);
}
$studentStatusString=trim($sessionHandler->getSessionVariable('Placement_UploadStudentDetails'));
if($studentStatusString==''){
    die('Upload Information Missing');
}

if($mode==0){
  $fileName = "StudentDetails_Inconsistencies.txt";
}
else{
  $fileName = "StudentDetailsUploaded.txt";  
}
ob_end_clean();
header("Cache-Control: public, must-revalidate");
header("Pragma: hack"); 
header("Content-Type: application/octet-stream");
header("Content-Length: " .strlen($studentStatusString));
header('Content-Disposition: attachment; filename="'.$fileName.'"');
header("Content-Transfer-Encoding: text\n");
echo $studentStatusString;
die;
?>
</body>
</html>
<?php 
// $History: testTypeReportPrint.php $
?>