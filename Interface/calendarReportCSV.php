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
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddEvent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Calendar Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Calendar/calendarCSV.php");
?>
</body>
</html>
<?php 
// $History: calendarReportCSV.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Interface
//access permission updated
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/08/09    Time: 17:09
//Created in $/LeapCC/Interface
//Corrected "Event Masters" bugs as pointed by Kanav Sir
?>