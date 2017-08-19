<?php 

//-------------------------------------------------------
//  This File outputs the Student Labels report in csv format
//
//
// Author :Ajinder Singh
// Created on : 21-may-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
ob_start();
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$linkHeading="Final Marks Report Print CSV"; 
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  $linkHeading="Display Final Internal Marks Report Print CSV";  
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
    require_once(TEMPLATES_PATH . "/StudentReports/listFinalInternalReportPrintCSVNew.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: finalInternalReportPrintCSV.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/06/09   Time: 1:30p
//Updated in $/LeapCC/Interface
//updated access defines
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/07/09    Time: 6:27p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/21/09    Time: 6:55p
//Created in $/LeapCC/Interface
//file added for final report csv version
//

?>
