<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToNumbers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Admin Send Message CSV</title>
</head>
<body>
<?php 
   require_once(TEMPLATES_PATH . "/AdminMessage/adminSendMessageCSV.php");
?>
</body>
</html>
<?php 
// $History: adminSentMessagesDocument.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:13
//Created in $/LeapCC/Interface
//Created "Send SMS" modules for sending SMSs to numbers entered by the
//end user
?>