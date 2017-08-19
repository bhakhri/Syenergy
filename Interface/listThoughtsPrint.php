<?php 

//-------------------------------------------------------
//  This File outputs the thoughts report to the Printer
//
//
// Author :Parveen Sharma
// Created on : 20-03-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
define('MODULE','ThoughtsMaster');
define('ACCESS','view');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Thoughts Report Print </title>
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
    require_once(TEMPLATES_PATH . "/Thoughts/listThoughtsReportPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: listThoughtsPrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:35a
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:33a
//Created in $/Leap/Source/Interface
//file added
//

?>
