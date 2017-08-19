<?php 
//-------------------------------------------------------
//  This File outputs the subject to class to the CSV
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopRouteMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Stop Route Mapping Report CSV </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/BusStop/stopToRouteMappingCSV.php");
?>
</body>
</html>
<?php 
// $History: assignSubjectToClassCSV.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:14a
//Created in $/LeapCC/Interface
//Intial checkin
?>
