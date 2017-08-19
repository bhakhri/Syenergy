<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in FEE HEAD VALUES Form
//
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeCycleFine/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Print </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
 
</head>
<body>
    <?php 
//    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ScCourse/listSubjectPrint.php");
//    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: listSubjectPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/24/08   Time: 2:08p
//Created in $/Leap/Source/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/24/08   Time: 1:53p
//Created in $/Leap/Source/Interface
//initial checkinm
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/15/08   Time: 5:57p
//Created in $/Leap/Source/Interface
?>
