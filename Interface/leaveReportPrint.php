<?php 
//-------------------------------------------------------
//  This File outputs the city report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 23.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Leave Type Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Leave/leavePrint.php");
?>
</body>
</html>
<?php 
// $History: degreeReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:22p
//Created in $/Leap/Source/Interface
//Added functionality for quota report print
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:12p
//Created in $/Leap/Source/Interface
//Added functionality for quota report print
?>
