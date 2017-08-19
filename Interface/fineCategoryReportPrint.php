<?php 
//-------------------------------------------------------
// This File outputs the fine category report to the Printer
// Author :Dipanjan Bhattacharjee
// Created on : 02.07.2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Category Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>
<script type="text/javascript">
function printout()
{
	document.getElementById('printing').style.display='none';
	window.print();
}
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Fine/fineCategoryPrint.php");
?>
</body>
</html>
<?php 
// $History: fineCategoryReportPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 16:47
//Updated in $/LeapCC/Interface
//Changed html and model file names in "Fine Category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:07
//Created in $/LeapCC/Interface
//Created "Fine Category Master" module
?>