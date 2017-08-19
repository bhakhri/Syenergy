<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in attendance register print
//
//
//
// Author :Parveen Sharma
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherAttendanceRegister');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifTeacherNotLoggedIn();    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Attendance Register Report Print  </title>
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
    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/attendanceRegisterReportPrint.php"); 
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

<?php 
//$History: attendanceRegisterPrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/17/10    Time: 12:20p
//Updated in $/LeapCC/Interface/Teacher
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/17/10    Time: 10:18a
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/19/10    Time: 12:10p
//Updated in $/LeapCC/Interface
//validation and format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 4:28p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:30p
//Created in $/LeapCC/Interface
//initial checkin
//
 
?>
