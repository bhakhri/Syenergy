<?php 
//-------------------------------------------------------
//  This File outputs the TestType report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackQuestionsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Questions Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>
<script type="text/javascript">
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/FeedBack/feedBackQuestionsPrint.php");
?>
</body>
</html>
<?php 
// $History: feedBackQuestionsReportPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:13
//Created in $/LeapCC/Interface
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
?>