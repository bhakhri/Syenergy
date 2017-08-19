<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in FEE HEAD VALUES Form
//
//
//
// Author :Parveen Sharma
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceCodesMaster');  
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();   
//require_once(BL_PATH . "/FeeCycleFine/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Code Report Print </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
 
</head>
<body>
    <?php 
//    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AttendanceCode/attendancePrint.php");
//    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: attendaceCodeReportPrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:30p
//Created in $/LeapCC/Interface
//initial checkin
//
 
?>
