<?php 

//-------------------------------------------------------
// It contains the contents send to printer
// Author : Saurabh Thukral
// Created on : (13.08.2012)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Receipt Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style type="text/css" media="print">
    @page port {size: portrait;}
    @page land {size: landscape;}
    .portrait {page: port;}
    .landscape {page: land;}
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
    require_once(TEMPLATES_PATH . "/Student/collectStudentFine.php");  	
	//require_once(TEMPLATES_PATH . "/Student/collectStudentFinePDF.php"); 
?>
</body>
</html>
<?php 
?>
