<?php 

//-------------------------------------------------------
// It contains the contents salary sheet send to printer
//
//
// Author :Abhiraj
// Created on : 2-May-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Salary Sheet Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
	BR.page { page-break-after: always }
</style>
<script type="text/javascript">

    //window.print();
    
</script>
</head>
<body>
<?php 
    //require_once(TEMPLATES_PATH . "/header.php");														
    require_once(TEMPLATES_PATH . "/Payroll/printSalarySheet.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
?>