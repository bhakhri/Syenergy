<?php
//-------------------------------------------------------
//  This File Teacher load time table for print format
//
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayLoadTeacherTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Teacher Load Time Table </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

</script>

</head>
<body>
    <?php 
  //  require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/listLoadTimeTablePrint.php");
   // require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: displayLoadTeacherTimeTablePrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 6:29p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 3:16p
//Created in $/Leap/Source/Interface
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/14/09    Time: 1:34p
//Updated in $/Leap/Source/Interface
//Access rights set
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/20/08   Time: 3:35p
//Created in $/Leap/Source/Interface
//intial checkin
?>