<?php 
//-------------------------------------------------------
//  This File outputs the search teacher to the Printer for subject centric
//
// Author :Rajeev Aggarwal
// Created on : 17-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
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
function printout(){

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
    require_once(TEMPLATES_PATH . "/TimeTable/teacherTimeTablePrint.php");
?>
</body>
</html>
<?php 
// $History: teacherTimeTablePrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/19/09    Time: 10:30a
//Updated in $/LeapCC/Interface
//details link add show the teacher time table 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 6:49p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 6:46p
//Created in $/Leap/Source/Interface
//file added
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/27/09    Time: 12:12p
//Updated in $/Leap/Source/Interface
//added Management Define.
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/17/08    Time: 3:48p
//Created in $/Leap/Source/Interface
//initial checkin
?>