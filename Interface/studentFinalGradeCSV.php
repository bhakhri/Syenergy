<?php 
//-------------------------------------------------------
//  This File prints student test wise marks report in CSV
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentFinalGrade');       
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Apply Student Final Grade Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/StudentReports/studentFinalGradeReportCSV.php");     
?>
</body>
</html>
<?php 
// $History: studentTestWiseMarksReportCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/01/10    Time: 2:33p
//Created in $/LeapCC/Interface
//initial checkin
//

?>