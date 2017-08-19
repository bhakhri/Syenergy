<?php 
//-------------------------------------------------------
//  This File outputs the Role report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Workshop Report CSV </title>
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
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/workshopReportPrintCSV.php");
?>
</body>
</html>
<?php 
// $History: workshopPrintCSV.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:16p
//Updated in $/LeapCC/Interface/Teacher
//added access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Interface/Teacher
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:40p
//Created in $/LeapCC/Interface/Teacher
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:39p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Interface
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 1:04p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:11p
//Created in $/Leap/Source/Interface
//inital checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 14/01/09   Time: 11:22
//Updated in $/Leap/Source/Interface
//Added Access Rights
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:31a
//Created in $/Leap/Source/Interface
//Added functionality for role report print
?>
