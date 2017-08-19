<?php 
//-------------------------------------------------------
//  This File prints teacher survery feedback report printing
//
// Author :Rajeev Aggarwal
// Created on : 02-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeFeedbackReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Teacher Survery Feedback Report </title>
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
    require_once(TEMPLATES_PATH . "/EmployeeReports/scTeacherFeedBackStatisticsPrint.php");
    //require_once(TEMPLATES_PATH . "/ffooter.php");
?>
</body>
</html>
<?php 
////$History: scTeacherFeedBackStatisticsPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/18/09    Time: 7:27p
//Created in $/Leap/Source/Interface
//Intial checkin for graphical view of feeback question wise
?>