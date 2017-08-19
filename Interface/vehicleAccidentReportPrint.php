<?php 
//-------------------------------------------------------
//  This File outputs the city report to the Printer
//
// Author :Jaineesh
// Created on : 28.01.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Accident Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Vehicle/vehicleAccidentPrint.php");
?>
</body>
</html>
<?php 
// $History: vehicleAccidentReportPrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 5:10p
//Created in $/Leap/Source/Interface
//new files for print of vehicle details
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/07/09   Time: 13:33
//Created in $/Leap/Source/Interface
//Done bug fixing.
//Bug ids---
//0000793,0000792,0000791,0000790,0000789,0000788,
//0000787,0000786
?>