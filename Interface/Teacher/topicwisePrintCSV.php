<?php 
//-------------------------------------------------------
//  This File outputs the Topicswise report CSV
//
// Author :Parveen Sharma
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');  
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Topicwise Report CSV </title>
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
    require_once(TEMPLATES_PATH ."/Teacher/TeacherActivity/topicwiseReportPrintCSV.php");
?>
</body>
</html>
<?php 
// $History: topicwisePrintCSV.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:16p
//Updated in $/LeapCC/Interface/Teacher
//added access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/26/09    Time: 5:11p
//Updated in $/LeapCC/Interface/Teacher
//function, condition, formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/26/09    Time: 4:57p
//Updated in $/LeapCC/Interface/Teacher
//file name updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/26/09    Time: 4:55p
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//

?>
