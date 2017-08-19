<?php 
//------------------------------------------------------------------------
//  This File outputs the details Time Table Report CSV format
//
// Author :Parveen Sharma
// Created on : 06-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
//header("Content-Disposition: attachment; filename=TimeTableReport.doc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Multi Utility Time Table Report CSV  </title>
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
	function createBlankTD($i,$str='<td  valign="middle" align="center">---</td>'){
		return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
	}
    require_once(TEMPLATES_PATH . "/TimeTable/detailsTimeTableReportCSV.php");
?>
</body>
</html>
<?php 
// $History: detailsTimeTableReportCSV.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10-04-10   Time: 4:47p
//Updated in $/LeapCC/Interface
//added multiple utility time table in management login 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/21/09   Time: 12:38p
//Created in $/LeapCC/Interface
//initital checkin
//

?>