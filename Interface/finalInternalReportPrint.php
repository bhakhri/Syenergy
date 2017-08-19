<?php 

//-------------------------------------------------------
//  This File outputs the testwise marks report to the Printer
//
//
// Author :Ajinder Singh
// Created on : 14-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

$linkHeading="Final Marks Report Print"; 
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  $linkHeading="Display Final Internal Marks Report Print";  
  define('MODULE','DisplayFinalInternalReport');
  define('ACCESS','view');
  define('MANAGEMENT_ACCESS',1);  
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  define('MODULE','FinalInternalReport');
  define('ACCESS','view');
  define('MANAGEMENT_ACCESS',1);  
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME.": ".$linkHeading; ?></title>  
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
    BR.page { page-break-after: always }
    @page port {size: portrait;}
    @page land {size: landscape;}
    .portrait {page: port;}
    .landscape {page: land;}
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
    require_once(TEMPLATES_PATH . "/StudentReports/listFinalInternalReportPrintNew.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
// $History : finalInternalReportPrint.php $
//



?>
