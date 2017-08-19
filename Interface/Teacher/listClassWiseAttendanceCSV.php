<?php 
//-------------------------------------------------------
//This file is used as csv version for display attendance
//
// Author :Rajeev Aggarwal
// Created on : 08-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassWiseAttendanceList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Attendance CSV</title>
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
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listClassWiseAttendanceCSV.php");
?>
</body>
</html>
<?php 
// $History: listClassWiseAttendanceCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/08/09    Time: 1:04p
//Created in $/LeapCC/Interface/Teacher
//added print and export to csv functionality
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/08/09    Time: 12:53p
//Created in $/SnS/Interface/Teacher
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/08/09    Time: 12:13p
//Created in $/Leap/Source/Interface/Teacher
//Intial checkin
?>
