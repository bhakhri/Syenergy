<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Gurkeerat Sidhu
// Created on : 15-02-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_TeacherGpaReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Teacher Final Report (Advanced) CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/teacherFinalCSV.php");
?>
</body>
</html>
<?php 
// $History: teacherFinalReportCSV.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/16/10    Time: 12:03p
//Created in $/LeapCC/Interface
//created file under feedback teacher final report
//

?>