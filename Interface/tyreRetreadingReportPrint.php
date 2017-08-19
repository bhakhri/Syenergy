<?php 
//-------------------------------------------------------
//  This File outputs the search tyre retreading to printer
//
// Author :Jaineesh
// Created on : 02-02-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TyreRetreadingReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Tyre Retreading Report Print </title>
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
    require_once(TEMPLATES_PATH . "/TyreRetreading/tyreRetreadingReportPrint.php");
?>
</body>
</html>
<?php 
// $History: tyreRetreadingReportPrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/02/10    Time: 5:19p
//Created in $/Leap/Source/Interface
//new file for tyre retreading print
//
?>