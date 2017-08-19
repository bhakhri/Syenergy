<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceSummary');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Summary Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/attenanceSummeryCSV.php");
?>
</body>
</html>
<?php 
// $History: attendanceSummeryReportCSV.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 19:23
//Created in $/LeapCC/Interface/Teacher
//Created "Attendance Summary" module in teacher login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 18/04/09   Time: 18:46
//Created in $/Leap/Source/Interface/Teacher
//Completed Attendance Summery Report
?>