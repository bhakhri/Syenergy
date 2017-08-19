<?php 
//-------------------------------------------------------
//  This File outputs the fee collection to the CSV for subject centric
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCollection');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Collection Report CSV </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Student/feeCollectionCSV.php");
?>
</body>
</html>
<?php 
// for VSS
// $History: feeCollectionCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:59p
//Created in $/LeapCC/Interface
//Intial Checkin
?>