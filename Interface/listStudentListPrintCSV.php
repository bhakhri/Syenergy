<?php 

//-------------------------------------------------------
//  This File outputs the Student Labels report in csv format
//
//
// Author :Arvind Singh Rawat                                        
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
ob_start();

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentList');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(); 
require_once(BL_PATH . "/StudentReports/initListStudentListsReports.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student List Report </title>
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
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentListPrintCSV.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: listStudentListPrintCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/21/09    Time: 2:42p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/09    Time: 4:32p
//Updated in $/Leap/Source/Interface
//issue fix search filter added
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/23/08    Time: 6:00p
//Created in $/Leap/Source/Interface
//initial chekin
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/22/08    Time: 3:42p
//Created in $/Leap/Source/Interface
//file added for student filter - sc
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/13/08    Time: 2:48p
//Created in $/Leap/Source/Interface
//file added for "allDetailsReport.php" - csv part
//

?>
