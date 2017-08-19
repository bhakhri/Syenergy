<?php 
//-------------------------------------------------------
//  This File outputs the TestType report to the Printer
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignFinetoRoles');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Role to Fines Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>
<script type="text/javascript">
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Fine/assignFineToRolePrint.php");
?>
</body>
</html>
<?php 
// $History: assignFineToRoleReportPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:15
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000739,0000740,0000746,0000747,0000748,0000752
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 27/07/09   Time: 16:05
//Created in $/LeapCC/Interface
//Done bug fixing.
//bug ids---0000697 to 0000702
?>