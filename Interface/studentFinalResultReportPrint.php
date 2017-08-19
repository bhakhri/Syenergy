<?php 
//-------------------------------------------------------
//  This File outputs the student final marks to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 09-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInfo');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Final Marks Print </title>
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
	function createBlankTD($i,$str='<td  valign="middle" align="center">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
    require_once(TEMPLATES_PATH . "/Student/studentFinalMarksPrint.php");
?>
</body>
</html>
<?php 
// $History: studentFinalResultReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/09/09    Time: 3:19p
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/09/09    Time: 1:42p
//Created in $/Leap/Source/Interface
//add print reports
?>