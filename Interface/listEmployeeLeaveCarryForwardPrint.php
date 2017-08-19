<?php 

//-------------------------------------------------------
//  This File prints test time period Form
//
// Author :Arvind Singh Rawat
// Created on : 22-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeaveCarryForward');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);  
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Employee Leave Carry Forward Report </title>
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
    require_once(TEMPLATES_PATH . "/EmployeeLeaveCarryForward/listEmployeeLeaveCarryForwardPrint.php");
    //require_once(TEMPLATES_PATH . "/ffooter.php");
?>
</body>
</html>
<?php 
////$History: smsDetailReportPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/02/09   Time: 3:40p
//Updated in $/LeapCC/Interface
//meta tag format updated (utf-8)
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
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Interface
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 11:27a
//Updated in $/Leap/Source/Interface
//


?>
