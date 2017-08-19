<?php 
//-------------------------------------------------------
//  This File outputs the city wise student to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','StudentDemographics');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: City wise Student Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Management/studentCityReportPrint.php");
?>
</body>
</html>
<?php 
// $History: cityWiseStudentPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-25   Time: 4:09p
//Updated in $/LeapCC/Interface/Management
//Updated with Demographics print report Access right DEFINE
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/30/09    Time: 5:47p
//Created in $/LeapCC/Interface/Management
//Intial checkin
 
?>