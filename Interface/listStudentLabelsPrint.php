<?php 

//-------------------------------------------------------
//  This File outputs the Student Labels report to the Printer
//
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/StudentReports/initListStudentLabelsReports.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Students Labels Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?> 
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
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentLabelsReport.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 

////$History: listStudentLabelsPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:44a
//Created in $/Leap/Source/Interface
//file added for : creating studentLabels report
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/11/08    Time: 12:39p
//Created in $/Leap/Source/Interface
//Added one new file for StudentLists Report Module
?>
