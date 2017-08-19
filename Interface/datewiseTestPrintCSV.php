<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DateWiseTestReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Date Wise Test Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
   require_once(TEMPLATES_PATH . "/StudentReports/datewiseTestReportPrintCSV.php"); 
?>
</body>
</html>
<?php 
// $History: datewiseTestPrintCSV.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/24/09   Time: 3:16p
//Updated in $/LeapCC/Interface
//report heading name udpated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/07/09   Time: 14:38
//Created in $/LeapCC/Interface
//Done bug fixing.
//bug ids---0000803,0000804
?>