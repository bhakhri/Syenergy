<?php 
//-------------------------------------------------------
//  This File prints percentage wise attendance report in CSV
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PercentageWiseAttendanceReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
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
<title><?php echo SITE_NAME;?>: Percentage wise Attendance Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/StudentReports/studentPercentageWiseReportPrintCSVNEW.php");     
?>
</body>
</html>
<?php 
// $History: studentPercentageWiseReportCSV.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/04/09   Time: 10:41a
//Updated in $/LeapCC/Interface
//role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/14/09   Time: 12:13p
//Created in $/LeapCC/Interface
//initial checkin
//
?>
