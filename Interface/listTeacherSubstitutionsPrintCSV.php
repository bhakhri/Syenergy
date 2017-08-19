<?php 
//-------------------------------------------------------
//  This File outputs the payment status to CSV for subject centric
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherSubstitutions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Substitutions Report CSV</title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/TimeTable/listTeacherSubstitutionsPrintCSV.php");
?>
</body>
</html>
<?php 
// for VSS
// $History: listTeacherSubstitutionsPrintCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 4:00p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 3:31p
//Created in $/SnS/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 11:42a
//Created in $/Leap/Source/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/18/09    Time: 3:12p
//Created in $/SnS/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/17/09    Time: 11:59a
//Created in $/Leap/Source/Interface
//initial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 11/06/08   Time: 1:26p
//Updated in $/Leap/Source/Interface
//updated with "Access" rights parameter
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/24/08    Time: 5:21p
//Created in $/Leap/Source/Interface
//intial checkin
?>