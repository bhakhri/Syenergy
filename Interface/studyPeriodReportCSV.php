<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudyPeriodMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Study Period Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/StudyPeriod/studyPeriodCSV.php");
?>
</body>
</html>
<?php 
// $History: studyPeriodReportCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/08/09    Time: 14:31
//Created in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000825,0000826,0000833,0000834,0000835,0000836,0000837 
?>