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
define('MODULE','ADVFB_Options');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Advanced Answer Set Options Report Print </title>
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
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/feedbackAdvOptionsPrint.php");
?>
</body>
</html>
<?php 
// $History: feedbackAdvOptionsReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:18p
//Created in $/LeapCC/Interface
//Created file under Feedback Advanced Answer Set Options Module
//

?>
