<?php
//-----------------------------------------------------------------------------
//  To generate Student ICard Print
//
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeIcardReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee I-Card Report Print </title>
<?php 
// echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="all" title="" href="<?php echo CSS_PATH;?>/css.css" />
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
    require_once(TEMPLATES_PATH . "/Icard/icardEmployeeReportPrint.php");
?>
</body>
</html>
<?php 
// $History: icardEmployeePrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:08p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/12/09    Time: 4:40p
//Updated in $/LeapCC/Interface
//inital checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/12/09    Time: 3:45p
//Created in $/LeapCC/Interface
//icard file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/07/09    Time: 4:45p
//Updated in $/Leap/Source/Interface
//template base code settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 3:43p
//Created in $/Leap/Source/Interface
//initial checkin
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 11/06/08   Time: 10:53a
//Updated in $/Leap/Source/Interface
//added "Access" rights parameter
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/19/08    Time: 10:38a
//Updated in $/Leap/Source/Interface
//updated formatting and changed subject to course
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/05/08    Time: 6:14p
//Created in $/Leap/Source/Interface
//intial checkin
?>