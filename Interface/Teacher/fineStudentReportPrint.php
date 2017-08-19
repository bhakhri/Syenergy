<?php 
//-------------------------------------------------------
// This File outputs the fine category report to the Printer
// Author :Rajeev Aggarwal
// Created on : 02.07.2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Student Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/fineStudentPrint.php");
?>
</body>
</html>
<?php 
// $History: fineStudentReportPrint.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:16p
//Updated in $/LeapCC/Interface/Teacher
//added access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:21p
//Created in $/LeapCC/Interface/Teacher
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:29p
//Created in $/LeapCC/Interface
//Intial checkin for fine student
?>