<?php 
//-------------------------------------------------------
//  This File outputs the search student to attendance register print the CSV
//
// Author :PArveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceRegister');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1); 
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Attendance Register Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    //require_once(TEMPLATES_PATH . "/AdminTasks/attendanceRegisterReportCSV.php");
    require_once(TEMPLATES_PATH . "/AdminTasks/attendanceRegisterCSV.php");
?>
</body>
</html>
<?php 
// $History: attendanceRegisterCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 4:28p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:30p
//Created in $/LeapCC/Interface
//initial checkin
//
 
?>