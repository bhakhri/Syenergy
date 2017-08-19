<?php 
//-------------------------------------------------------
//  This File outputs the payment status to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==3){
   UtilityManager::ifParentNotLoggedIn(true);  
   $studentId = $sessionHandler->getSessionVariable('StudentId'); 
}
else if($roleId==4){
   UtilityManager::ifStudentNotLoggedIn(true);  
   $studentId = $sessionHandler->getSessionVariable('StudentId'); 
}
else{
  UtilityManager::ifNotLoggedIn();
  $studentId = $REQUEST_DATA['studentId'];
}
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Student/feePrint.php");
?>
</body>
</html>
<?php 
// for VSS
// $History: feeStatusReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/07/09    Time: 6:27p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/05/08    Time: 6:07p
//Created in $/Leap/Source/Interface
//intial checkin
?>
