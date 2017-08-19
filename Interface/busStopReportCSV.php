<?php 
//-------------------------------------------------------
//  This File outputs the Bus Stop report to the CSV
//
// Author :Jaineesh
// Created on : 22.10.2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Stop Report Print CSV </title>
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
	document.getElementById('printing').style.display='none';
	window.print();
}
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/BusStop/busStopPrintCSV.php");
?>
</body>
</html>
<?php 
// $History: busStopReportCSV.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/22/09   Time: 2:57p
//Created in $/LeapCC/Interface
//new excel file for bus stop
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 13:05
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids--Issues[03-june-09].doc(1 to 11)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:45p
//Created in $/Leap/Source/Interface
//Added functionality for bus stop report print
?>
