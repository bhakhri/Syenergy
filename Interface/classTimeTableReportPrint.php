<?php 
//-------------------------------------------------------
//  This File outputs the class timetable to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 05-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Time Table Print </title>
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
    require_once(TEMPLATES_PATH . "/TimeTable/classTimeTablePrint.php");
?>
</body>
</html>
<?php 
// $History: classTimeTableReportPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/04/08    Time: 5:54p
//Created in $/Leap/Source/Interface
//intial checkin
?>
