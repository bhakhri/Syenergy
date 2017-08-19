<?php
//-----------------------------------------------------------------------------
//  To generate Student BusPass Print
//
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance History Report Print </title>
<?php 
// echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="all" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style>
BR.page { page-break-after: always }
</style>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/AdminTasks/attendanceHistoryPrint.php");
?>
</body>
</html>
<?php 
// $History: attendanceHistoryPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:09
//Created in $/LeapCC/Interface
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
?>