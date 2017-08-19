<?php
//-----------------------------------------------------------------------------
//  To generate Attendance Short functionality        
//
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAttendanceShortReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Attendance Short Report Print </title>
<?php 
// echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="all" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style>
    .brpage { page-break-after: always }
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
    require_once(TEMPLATES_PATH . "/StudentReports/studentAttendanceShortReportPrint.php");
?>
</body>
</html>
<?php 
// $History: studentAttendanceShortPrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/23/10    Time: 5:41p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/18/09   Time: 2:40p
//Created in $/Leap/Source/Interface
//initial checkin
//
 
?>