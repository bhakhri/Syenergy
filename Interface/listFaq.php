<?php
//-------------------------------------------------------
//  THIS FILE used to send email to students
//
// Author : Dipanjan Bhattacharjee
// Created on : (19.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Frequently Asked Questions related to Admin's functionality </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
echo UtilityManager::includeJS("content_down.js"); 
?> 
 

 
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/ListFaq/listFaqContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listBulkSMS.php $ 

?>