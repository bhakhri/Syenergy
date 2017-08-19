<?php 

//-------------------------------------------------------
//  This File outputs the Student List report to the Printer
//
//
// Author :Arvind Singh Rawat
// Created on : 10-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$mod = $REQUEST_DATA['mod'];
define('MODULE',$mod);
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Topic Report </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?> 
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
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
    require_once(TEMPLATES_PATH . "/SubjectTopic/listSubjectTopicPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

<?php 

//$History: listSubjectTopicPrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/01/09    Time: 3:22p
//Updated in $/LeapCC/Interface
//formatting & spelling correct
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Created in $/LeapCC/Interface
//print & csv file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 1:22p
//Created in $/Leap/Source/Interface
//print & CSV files added
//


?>
