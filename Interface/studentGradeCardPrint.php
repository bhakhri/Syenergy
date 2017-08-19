<?php
//-----------------------------------------------------------------------------
//  To generate Student ICard Print
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentGradeCardReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Grade Card Report Print </title>
<?php 
// echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="all" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style>
    .brpage { page-break-after: always }
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
    //require_once(TEMPLATES_PATH . "/ScGradeCard/scStudentGradeCardReportPrint.php");
    if(GRADE_CARD_DESIGN_FORMAT=='2') { 
       require_once(TEMPLATES_PATH . "/GradeCardReport/studentGradeCardReport2.php");
    }
    else if(GRADE_CARD_DESIGN_FORMAT=='3') { 
       require_once(TEMPLATES_PATH . "/GradeCardReport/studentGradeCardReport3.php");
    }
    else {
       require_once(TEMPLATES_PATH . "/GradeCardReport/studentGradeCardReport.php");  
    }
?>
</body>
</html>
