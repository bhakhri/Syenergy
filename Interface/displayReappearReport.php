<?php 

//-------------------------------------------------------
//  This File outputs the Student Interneral Re-appear report to the Printer
//
//
// Author :Parveen Sharma
// Created on : 10-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayStudentReappearReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Student Internal Re-appear Report Print </title>
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
    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listReappearReportPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

<?php 

//$History: displayReappearReport.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/18/10    Time: 4:11p
//Created in $/LeapCC/Interface
//initial checkin
//

?>
