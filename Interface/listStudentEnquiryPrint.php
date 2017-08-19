<?php 

//-------------------------------------------------------
//  This File prints test time period Form
//
// Author :Arvind Singh Rawat
// Created on : 22-Oct-2008
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
<title><?php echo SITE_NAME;?>: Student Enquiry Report Print </title>
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
    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentEnquiry/studentEnquiryReportPrint.php");
    //require_once(TEMPLATES_PATH . "/ffooter.php");
?>
</body>
</html>
<?php 
////$History: listStudentEnquiryPrint.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 6:26p
//Updated in $/LeapCC/Interface
//validation checks & spelling correct 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/30/09    Time: 11:27a
//Updated in $/LeapCC/Interface
//formating & conditions update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 7:14p
//Created in $/LeapCC/Interface
//initial checkin
//

?>
