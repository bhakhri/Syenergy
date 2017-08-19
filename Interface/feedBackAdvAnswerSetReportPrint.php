<?php 
//-------------------------------------------------------
//  This File outputs the TestType report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AnswerSet');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Answer Set Report Print </title>
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
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/feedBackAdvAnswerSetPrint.php");
?>
</body>
</html>
<?php 
// $History: feedBackAdvAnswerSetReportPrint.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 1:02p
//Updated in $/LeapCC/Interface
//updated folder name
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 6:13p
//Created in $/LeapCC/Interface
//Created file under Feedback Advanced AnswerSet module
//

?>
