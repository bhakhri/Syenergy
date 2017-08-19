<?php 
//------------------------------------------------------------------------
//  This File outputs the search teacher to the Printer for subject centric
//
// Author :Parveen Sharma
// Created on : 06-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayTimeTableReport');
define('ACCESS','view');    
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Multi Utility Time Table Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style>
   .brpage { page-break-after: always }
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
    require_once(TEMPLATES_PATH . "/TimeTable/detailsTimeTableReportPrint.php");
?>
</body>
</html>

<?php 
// $History: detailsTimeTablePrint.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10-04-10   Time: 4:47p
//Updated in $/LeapCC/Interface
//added multiple utility time table in management login 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/25/09    Time: 10:52a
//Updated in $/LeapCC/Interface
//formatting & alignment updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/19/09    Time: 4:11p
//Created in $/LeapCC/Interface
//initial checkin
//

?>