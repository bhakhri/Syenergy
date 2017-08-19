<?php

//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Adhoc Concession</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">
// ajax search results ---end ///
/* function to print student profile report*/
function printStudentReport() { 
	path='<?php echo UI_HTTP_PATH;?>/Fee/studentFeesPrint.php ?>';
	window.open(path,"Student Fee","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
}


//populate list
window.onload=function(){ 
	printStudentReport();
}
</script>
</head>

</html>
