<?php
//-----------------------------------------------------------------------------
//  To generate Student BusPass Print
//
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentBusPass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Bus Pass Report Print </title>
<?php 
// echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="all" title="" href="<?php echo CSS_PATH;?>/css.css" />
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
    require_once(TEMPLATES_PATH . "/Icard/busPassReportPrint.php");
?>
</body>
</html>
<?php 
// $History: busPrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/16/09    Time: 3:53p
//Updated in $/LeapCC/Interface
//heading name change 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/15/09    Time: 11:21a
//Created in $/LeapCC/Interface
//initial checkin
//
?>