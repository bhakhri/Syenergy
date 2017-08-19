<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Gurkeerat Sidhu
// Created on : 10-1-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Advanced Label Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/feedBackAdvLabelCSV.php");
?>
</body>
</html>
<?php 
// $History: feedBackAdvLabelReportCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:26
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002811
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 1:07p
//Created in $/LeapCC/Interface
//created file under feedback advanced label module

?>