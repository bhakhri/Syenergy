<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_CommentsReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:  Feedback Comments Report (Advanced) CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/feedbackCommentsReportCSV.php");
?>
</body>
</html>
<?php 
// $History: feedbackCommentsReportCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:59p
//Created in $/LeapCC/Interface
//Created "Feedback Comments Report"
?>