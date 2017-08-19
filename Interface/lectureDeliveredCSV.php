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
define('MODULE','EmployeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Lecture Details Report CSV </title>
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
    require_once(TEMPLATES_PATH . "/EmployeeReports/lectureDeliveredReportCSV.php");
?>
</body>
</html>
<?php 
// $History: lectureDeliveredCSV.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:03p
//Updated in $/LeapCC/Interface
//updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/17/09    Time: 5:42p
//Updated in $/LeapCC/Interface
//condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/16/09    Time: 4:26p
//Created in $/LeapCC/Interface
//initital checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:18p
//Created in $/Leap/Source/Interface
//initial checkin 
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
