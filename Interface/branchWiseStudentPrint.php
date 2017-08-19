<?php 
//-------------------------------------------------------
//  This File outputs the branch wise student to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 23-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDemographics');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Branch wise Student Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Student/studentBranchReportPrint.php");
?>
</body>
</html>
<?php 
// $History: branchWiseStudentPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-25   Time: 5:25p
//Updated in $/LeapCC/Interface
//updated with access right DEFINE
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 09-08-18   Time: 3:15p
//Created in $/LeapCC/Interface
//Intial checkin
?>