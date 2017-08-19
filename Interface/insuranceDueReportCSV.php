<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceDueReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Insurance Due Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Bus/insuranceDueReportCSV.php");
?>
</body>
</html>
<?php 
// $History: insuranceDueReportCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Administrator Date: 4/06/09    Time: 11:03
//Created in $/LeapCC/Interface
//Added insurance due report's print & export to excell files
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:36
//Created in $/Leap/Source/Interface
//Added insuranceDueReportCSV and insuranceDueReportPrint files
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:26
//Created in $/SnS/Interface
//Added "InsuranceDue Report" module
?>