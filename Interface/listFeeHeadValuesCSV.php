<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValues');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Head Values Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
</head>
<body>
<?php 
   require_once(TEMPLATES_PATH . "/FeeHeadValues/feeHeadValuesCSV.php");    
?>
</body>
</html>
<?php 
// $History: listFeeHeadValuesCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/17/09    Time: 11:08a
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Interface
//Added "Print" and "Export to excell" in subject and subjectType modules
?>