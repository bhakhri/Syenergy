<?php 

//-------------------------------------------------------
//  This File outputs the Student List report to the Printer
//
//
// Author :Arvind Singh Rawat
// Created on : 10-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleFines');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Fee Cycle Fine Report CSV </title>
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
     require_once(TEMPLATES_PATH . "/FeeCycleFine/feeCycleFineCSV.php");    
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 

//$History: listFeeCycleFinePrintCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/08/09    Time: 4:37p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/30/09    Time: 5:05p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Created in $/LeapCC/Interface
//print & csv file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 1:22p
//Created in $/Leap/Source/Interface
//print & CSV files added
//


?>
