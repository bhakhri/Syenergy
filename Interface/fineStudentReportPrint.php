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
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fine Master Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Fine/fineStudentPrint.php");
?>
</body>
</html>
<?php 
// $History: fineStudentReportPrint.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Interface
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:29p
//Created in $/LeapCC/Interface
//Intial checkin for fine student
?>