<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Ajinder Singh
// Created on : 24-03-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestWiseMarksReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Wise Marks Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/StudentReports/listTestWiseMarksReportCSV.php");
?>
</body>
</html>
<?php 
// $History: testWiseMarksReportCSV.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/24/10    Time: 12:53p
//Created in $/LeapCC/Interface
//file added for testwise marks report - excel version
//

 
?>