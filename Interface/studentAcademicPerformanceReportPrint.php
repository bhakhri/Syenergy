<?php 

//-------------------------------------------------------
//  This File outputs the Student Labels report to the Printer
//
//
// Author :Ajinder Singh
// Created on : 29-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAcademicPerformanceReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Academic Performance Report (Pre Transfer) Print</title>
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
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentAcademicPerformanceReportPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: studentAcademicPerformanceReportPrint.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/10/09    Time: 11:21
//Updated in $/LeapCC/Interface
//Added link for "Student Academic Performance Report" and added access
//parameters
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 28/08/09   Time: 19:06
//Updated in $/LeapCC/Interface
//Created  "Student Academic Performance Report" module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/25/09    Time: 7:00p
//Created in $/LeapCC/Interface
//new file for student academic performance report
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/03/08    Time: 3:43p
//Created in $/Leap/Source/Interface
//file added for student performance report.
//


?>
