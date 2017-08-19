<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Gurkeerat Sidhu
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UserLoginReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
   if(trim($REQUEST_DATA['listView'])==1){ 
       $reportTitle= "Student not loggedIn Report";
   }
    else{
     $reportTitle= "User log in Report";
   }
?>
<title><?php echo SITE_NAME;?>: <?php echo $reportTitle; ?> CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/UserLogin/userLoginCSV.php");
?>
</body>
</html>
<?php 
// $History: userLoginReportCSV.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/22/10    Time: 5:50p
//Created in $/LeapCC/Interface
//created file under user login report
//


?>