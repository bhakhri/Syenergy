<?php 
//-------------------------------------------------------
//  This File outputs the Student Enquiry profile to the Printer
//
// Author :Parveen Sharma
// Created on : 22-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AddStudentEnquiry');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Enquiry Profile Print </title>
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
    require_once(TEMPLATES_PATH . "/StudentEnquiry/studentEnquiryProfilePrint.php");
?>
</body>
</html>
<?php 
// $History: studentEnquiryPrint.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/03/09    Time: 10:36a
//Updated in $/LeapCC/Interface
//set rights permission 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/29/09    Time: 6:27p
//Updated in $/LeapCC/Interface
// path name change
//

?>
