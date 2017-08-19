<?php 
//-------------------------------------------------------
//  This File outputs the student profile to the Printer
// Author :Nishu Bindal
// Created on : 10-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==4){
  UtilityManager::ifStudentNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fee Slip </title>
<?php 
	echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style>
BR.page { page-break-after: always }
</style>

<script type="text/javascript">

function printout()
{
	document.getElementById('hidePrint').style.display='none';
	window.print();
	document.getElementById('hidePrint').style.display='';
}
</script>
</head>
<body>
<?php
    global $sessionHandler;
    $isPDF = $sessionHandler->getSessionVariable('FEE_PRINT_DETAIL_PDF');
	if($isPDF=='1') {
      require_once(TEMPLATES_PATH . "/Fee/StudentFee/studentFeesPrintPDF.php");
    }
    else {
      require_once(TEMPLATES_PATH . "/Fee/StudentFee/studentFeesPrint.php");  
    }
?>
</body>
</html>

