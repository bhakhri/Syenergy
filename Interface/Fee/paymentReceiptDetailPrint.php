<?php 
//-------------------------------------------------------
// This File outputs the detail fees receipt to the Printer
// Author :Nishu Bindal
// Created on : 8-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Payment Receipt Print </title>
<?php 
	echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style type="text/css" media="print">
BR.page { page-break-after: always }
@page port {size: portrait;}
@page land {size: landscape;}
.portrait {page: port;}
.landscape {page: land;}
</style>
<script type="text/javascript">
function printout() {
	document.getElementById('hidePrint').style.display='none';
	window.print();
	document.getElementById('hidePrint').style.display='';
}
</script>
</head>
<body>
<?php 
    //require_once(TEMPLATES_PATH . "/Student/paymentDetailReceipt.php");
    require_once(TEMPLATES_PATH . "/Fee/PaymentHistory/paymentReceiptDetailsPrint.php");
?>
</body>
</html>

