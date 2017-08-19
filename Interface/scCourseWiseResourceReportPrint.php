<?php 

//-------------------------------------------------------
//  This File outputs the Coursewise Resource Print
//
//
// Author :Parveen Sharma
// Created on : 03-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CoursewiseResourceReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Coursewise Resource Report Print</title>
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
    require_once(TEMPLATES_PATH . "/StudentReports/scCourseWiseResourceReportPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: scCourseWiseResourceReportPrint.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 12:30p
//Updated in $/LeapCC/Interface
//role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/17/09    Time: 12:07p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/01/09   Time: 15:38
//Created in $/SnS/Interface
//Added "Coursewise resource report" module
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/08   Time: 4:12p
//Created in $/Leap/Source/Interface
//coursewise resource added
//


?>
