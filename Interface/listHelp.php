<?php 

//-------------------------------------------------------
//  This File contains show the help images 
//
// Author :Parveen Sharma
// Created on : 09-Nov-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
//UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Help </title>
<?php 
    require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
</head>
<body>
<?php 
    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Help/listHelpContents.php");
 //   require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>


<?php 
//$History: listHelp.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/16/09   Time: 12:31p
//Created in $/LeapCC/Interface
//file added
//

?>
