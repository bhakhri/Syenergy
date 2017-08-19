<?php 

//-------------------------------------------------------
//  Display Time Table of Student
//
//
// Author :Jaineesh
// Created on : 14-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDisplayTimeTable');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Time Table Report </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>

<?php
function createBlankTD($i,$str=''){
	if(empty($str)) {
		$str = '<td  valign="middle" align="center" >'.NOT_APPLICABLE_STRING.'</td>';
	}
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
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
    require_once(TEMPLATES_PATH . "/Student/printStudentTimeTableReport.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
?>