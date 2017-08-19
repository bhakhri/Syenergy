<?php 
//-------------------------------------------------------
//  This File outputs the user detail report to the Printer
//
//
// Author :Rajeev Aggarwal
// Created on : 14-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: All User Details Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Management/listAllUsersReportPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: userWisePrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/10/10    Time: 5:06p
//Updated in $/LeapCC/Interface/Management
//validation & fromat updated (toppers, avg, etc.)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/06/10    Time: 5:53p
//Created in $/LeapCC/Interface/Management
//initial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Interface
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:06p
//Created in $/LeapCC/Interface
//Intial Checkin
?>