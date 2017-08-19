<?php 
//-------------------------------------------------------
//  This File prints genral topic taught report printing
//
// Author :Parveen Sharma
// Created on : 01-06-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWiseTopicTaught');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Subject Wise Topic Taught Report Print </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>
<script type="text/javascript">
function printout()
{
    document.getElementById('printing').style.display='none';
    window.print();
}
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/teacherTopicCoveredPrint.php");
?>
</body>
</html>
<?php 
//$History: teacherTopicCoveredReportPrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:23p
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//

?>
