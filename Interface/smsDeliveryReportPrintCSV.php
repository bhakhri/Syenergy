<?php 
//-------------------------------------------------------
//  This File outputs the SMS Details to the CSV
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SMSDeliveryReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Messages List Reportf CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/SMSReports/smsDeliveryReportPrintCSV.php");
?>
</body>
</html>
<?php 
// $History: smsDetailReportPrintCSV.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/05/09    Time: 5:30p
//Updated in $/SnS/Interface
//role permission & file name format updated 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:11
//Created in $/SnS/Interface
//Added Sns System to VSS(Leap for Chitkara International School)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Interface
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 11:27a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/27/08   Time: 11:06a
//Created in $/Leap/Source/Interface
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 11/06/08   Time: 11:07a
//Updated in $/Leap/Source/Interface
//Updated with "Access" rights parameters
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/24/08    Time: 3:01p
//Created in $/Leap/Source/Interface
//intial checkin
?>