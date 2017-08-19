<?php 
//-------------------------------------------------------
//  This File outputs the search teacher to the Printer for subject centric
//
// Author :Rajeev Aggarwal
// Created on : 17-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherTimeTableDisplay');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: My Time Table Print </title>
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
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/teacherTimeTableReportPrint.php");
?>
</body>
</html>
<?php 
// $History: teacherTimeTableReportPrint.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:16p
//Updated in $/LeapCC/Interface/Teacher
//added access defines
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 13:03
//Created in $/LeapCC/Interface/Teacher
//Added the functionality of time table print in teacher section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 12:36
//Created in $/SnS/Interface/Teacher
//Added TimeTable Print Functionality in Teacher Section
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/17/08    Time: 3:48p
//Created in $/Leap/Source/Interface
//initial checkin
?>