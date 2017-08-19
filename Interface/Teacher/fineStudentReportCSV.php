<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Rajeev Aggarwal
// Created on : 29-07-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Student Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/fineStudentCSV.php");
?>
</body>
</html>
<?php 
// $History: fineStudentReportCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:16p
//Updated in $/LeapCC/Interface/Teacher
//added access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:57p
//Created in $/LeapCC/Interface/Teacher
//intial checkin
?>