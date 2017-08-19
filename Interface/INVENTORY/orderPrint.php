<?php 
//-------------------------------------------------------
//  This File outputs the TestType report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OrderMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Order Detail Report </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>
<script type="text/javascript">
</script>
</head>
<body>
<?php 
    require_once(INVENTORY_TEMPLATES_PATH . "/OrderMaster/orderPrint.php");
?>
</body>
</html>
<?php 
// $History: orderPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/09/09   Time: 10:56
//Updated in $/Leap/Source/Interface/INVENTORY
//Corrected add/edit code during order no entry and corrected interface
//path in print file
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:33
//Created in $/Leap/Source/Interface/INVENTORY
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:46
//Created in $/Leap/Source/Interface
//Added files for "Order Master" module
?>