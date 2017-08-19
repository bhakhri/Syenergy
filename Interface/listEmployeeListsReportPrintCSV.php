<?php 

//-------------------------------------------------------
//  This File outputs the Employee Labels report in csv format
//
//--------------------------------------------------------
ob_start();
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeList');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(); 
require_once(BL_PATH . "/EmployeeReports/initListEmployeeListsReports.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employees List Report CSV </title>
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
    require_once(TEMPLATES_PATH . "/EmployeeReports/listEmployeeReportPrintCSV.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: listEmployeeListsReportPrintCSV.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/15/09   Time: 3:14p
//Updated in $/LeapCC/Interface
//print & CSV report heading name updated (bug no. 1772)
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:02a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/08   Time: 5:21p
//Updated in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/07/08   Time: 1:05p
//Updated in $/Leap/Source/Interface
//file added for "scEmployeeListsReportPrintCSV.php" - csv part
//

?>
