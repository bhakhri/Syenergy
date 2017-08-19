<?php 
//-------------------------------------------------------
//  This File outputs the degree wise student to the Printer
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Degree wise Student Enquiry Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Index/studentDegreeEnquiryReportPrint.php");
?>
</body>
</html>
<?php 
// $History: degreeWiseEnquiryStudentPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/02/09    Time: 6:16p
//Created in $/LeapCC/Interface
//Intial checkin to display print report for student Enquiry
?>