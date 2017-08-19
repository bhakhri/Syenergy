<?php 
//-------------------------------------------------------
//  This File outputs the Calendar Events report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddEvent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Events Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Calendar/calendarPrint.php");
?>
</body>
</html>
<?php 
// $History: calendarReportPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Interface
//access permission updated
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 17:07
//Updated in $/LeapCC/Interface
//Corrected "Event Masters" as pointed by Kanav Sir
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 1:36p
//Created in $/Leap/Source/Interface
//Added functionality for calendar event report print
?>
