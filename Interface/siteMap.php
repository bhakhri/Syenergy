<?php
//-------------------------------------------------------
//  This File is used for site map
//
//
// Author :Ajinder Singh
// Created on : 21-Jan-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

	
define('MODULE','COMMON');
define('ACCESS','view');
if ($roleId == 2) {
	UtilityManager::ifTeacherNotLoggedIn();
}
else if ($roleId == 3) {
	UtilityManager::ifParentNotLoggedIn();
}
else if ($roleId == 4) {
	UtilityManager::ifStudentNotLoggedIn();
}
else if($roleId == 5) {
	UtilityManager::ifManagementNotLoggedIn();
}
else {
	UtilityManager::ifNotLoggedIn();
}


$roleId = $sessionHandler->getSessionVariable('RoleId');
if ($roleId == '' or empty($roleId)) {
	redirectBrowser(UI_HTTP_PATH.'/sessionError.php');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Site Map </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

</head>
<body>
	<?php 

	 require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/SiteMap/listSiteMap.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: siteMap.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/24/10    Time: 10:49a
//Updated in $/LeapCC/Interface
//fixed query error. FCNS No.1456
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 2/15/10    Time: 3:20p
//Updated in $/LeapCC/Interface
//done customization changes
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 1/22/10    Time: 12:01p
//Updated in $/LeapCC/Interface
//done changes for site map. FCNS No. 1113
//


?>