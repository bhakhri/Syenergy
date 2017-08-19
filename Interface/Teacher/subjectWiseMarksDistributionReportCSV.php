<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//                                                                         
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWisePerformanceComparisonReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
    
  UtilityManager::ifNotLoggedIn(); //for admin
}
else if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(); //for teachers
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Summery Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/subjectWiseMarksDistributionCSV.php");
?>
</body>
</html>
<?php 
// $History: subjectWiseMarksDistributionReportCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Interface/Teacher
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:10
//Created in $/LeapCC/Interface/Teacher
//created "Subject Wise Performance" report
?>