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
define('MODULE','BusCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Bus/busPrint.php");
?>
</body>
</html>
<?php 
// $History: busReportPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Interface
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 11:39
//Updated in $/Leap/Source/Interface
//Fixed bugs----
//bug ids--Leap bugs2.doc(10 to 15)
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 11:50
//Created in $/Leap/Source/Interface
//Added Files for bus masters
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:13
//Created in $/SnS/Interface
//Created Bus Master Module
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
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:45p
//Created in $/Leap/Source/Interface
//Added functionality for bus stop report print
?>