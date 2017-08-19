<?php 
//-------------------------------------------------------
//  This File outputs the search teacher to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 14-01-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayClassTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Class wise Time Table Print </title>
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
	function createBlankTD($i,$str='<td  valign="middle" align="center">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
    require_once(TEMPLATES_PATH . "/TimeTable/classWiseTimeTablePrint.php");
?>
</body>
</html>
<?php 
// $History: classWiseTimeTableReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/07/09    Time: 5:43p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/14/09    Time: 6:12p
//Created in $/LeapCC/Interface
//intial checkin
?>
