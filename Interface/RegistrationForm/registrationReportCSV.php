<?php 

//---------------------------------------------------------------------------------------
// 
//THIS FILE CONTAINS THE CODE FOR PRINTING THE FEE FUND ALLOCATION LISTS
//
// Author :Jaineesh
// Created on : 25.06.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
//
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentRegistrationReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Registration Report CSV </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
</script>

</head>
<body>
    <?php 
   // require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/RegistrationForm/Mentees/registrationDetailReportCSV.php");
   // require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History:  $
//
?>