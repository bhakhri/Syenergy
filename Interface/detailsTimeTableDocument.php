<?php 
//------------------------------------------------------------------------
//  This File outputs the search teacher to the Printer for subject centric
//
// Author :Parveen Sharma
// Created on : 06-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','DisplayTimeTableReport');
define('ACCESS','view');    
UtilityManager::ifNotLoggedIn();
//header("Content-Type: application/msword; name='word'");
//header("Content-Disposition: attachment; filename=TimeTableReport-".rand(0,1000).".doc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo SITE_NAME;?>: Multi Utility Time Table Report Print Document </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
    .page { page-break-after:always; }

</style>
<script type="text/javascript">
function printout(){
	document.getElementById('printing').style.display='none';
	window.print();
}
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/TimeTable/detailsTimeTableReportDocument.php");
?>
</body>
</html>
<?php 
// $History: detailsTimeTableDocument.php $
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10-04-10   Time: 4:47p
//Updated in $/LeapCC/Interface
//added multiple utility time table in management login 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/21/09   Time: 2:23p
//Updated in $/LeapCC/Interface
//docment & csv file download
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/19/09   Time: 2:19p
//Updated in $/LeapCC/Interface
//csv format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/25/09    Time: 10:52a
//Updated in $/LeapCC/Interface
//formatting & alignment updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/09    Time: 3:51p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/08/09    Time: 6:47p
//Created in $/Leap/Source/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/06/09    Time: 11:46a
//Created in $/Leap/Source/Interface
//file added
//
?>