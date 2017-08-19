<?php 
//-------------------------------------------------------
//  This File outputs the Student List report to the Printer
//
//
// Author :Rajeev Aggarwal
// Created on : 21-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypeDistributionReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Type Distribution Consolidated Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?> 
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
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
    require_once(TEMPLATES_PATH . "/StudentReports/testTypeReportPrint.php");
?>
</body>
</html>
<?php 
//$History: testTypeWiseReportPrint.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/15/09   Time: 4:38p
//Updated in $/LeapCC/Interface
//resolved issues0002238, 0002239, 0002241, 0002242, 0002243, 0002240,
//0002271
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Interface
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/08/09    Time: 5:51p
//Created in $/LeapCC/Interface
//Intail checkin: Added test type distribution 
?>