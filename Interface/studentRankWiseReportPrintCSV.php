<?php 
//-------------------------------------------------------
//  This File outputs the SMS Details to the CSV
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentRank');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Exam Rankwise Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/StudentReports/studentRankWiseCSV.php");   
?>
</body>
</html>
<?php 
// $History: studentRankWiseReportPrintCSV.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/11/10    Time: 3:12p
//Updated in $/LeapCC/Interface
//tag name updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/11/10    Time: 2:41p
//Updated in $/LeapCC/Interface
//tag name updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/12/09    Time: 2:15p
//Created in $/LeapCC/Interface
//file added
//


?>