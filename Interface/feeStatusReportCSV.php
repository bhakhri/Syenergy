<?php 
//-------------------------------------------------------
//  This File outputs the payment status to CSV for subject centric
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeReceiptStatus');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Payment Status Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Student/feeStatusCSV.php");
?>
</body>
</html>
<?php 
// for VSS
// $History: feeStatusReportCSV.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:59p
//Created in $/LeapCC/Interface
//Intial Checkin
?>