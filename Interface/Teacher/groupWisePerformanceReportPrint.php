<?php
//-------------------------------------------------------
//  This File outputs the Student List report to the Printer
//
//
// Author :Rajeev Aggarwal
// Created on : 21-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupWisePerformanceReport');
define('ACCESS','view');
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	  UtilityManager::ifTeacherNotLoggedIn(); //for teachers
}
else{
	UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Group Wise Performance Report </title>
<?php
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<script type="text/javascript">
</script>
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/groupWisePerformancePrint.php");
?>
</body>
</html>
<?php
//$History: groupWisePerformanceReportPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:56
//Created in $/LeapCC/Interface/Teacher
//Added files for "Group Wiser Performance Report"
?>