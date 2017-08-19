<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :PArveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceCodesMaster');  
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Code Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/AttendanceCode/attendanceCSV.php");
?>
</body>
</html>
<?php 
// $History: attendaceCodeReportCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:30p
//Created in $/LeapCC/Interface
//initial checkin
//
 
?>