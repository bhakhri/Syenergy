<?php 
//-------------------------------------------------------
//  This File outputs the installment report to the CSV for subject centric
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InstallmentDetailOfStudents');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Installment Details of Students Report CSV </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Student/installmentCSV.php");
?>
</body>
</html>
<?php 
// $History: installmentReportCSV.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/08/09    Time: 10:50a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:59p
//Created in $/LeapCC/Interface
//Intial Checkin
?>