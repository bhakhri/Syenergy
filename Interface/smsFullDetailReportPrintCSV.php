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
define('MODULE','MessagesCountList');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Messages Count List Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/SMSReports/smsFullDetailReportPrintCSV.php");
?>
</body>
</html>
<?php 
// $History: smsFullDetailReportPrintCSV.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/12/09    Time: 1:18p
//Updated in $/LeapCC/Interface
//file name change (lowercase formatting)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Interface
//code update search for & condition update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/28/08   Time: 11:30a
//Updated in $/Leap/Source/Interface
//change list formatting
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 1:08p
//Updated in $/Leap/Source/Interface
//sms details message search
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/27/08   Time: 11:35a
//Created in $/Leap/Source/Interface
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