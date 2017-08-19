<?php
//used for showing moodle dashboard
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ShowMoodle');
define('ACCESS','view');
global $sessionHandler;  
$moodleURL = $sessionHandler->getSessionVariable('MOODLE_URL');
if($sessionHandler->getSessionVariable('RoleId')==4) {
  UtilityManager::ifStudentNotLoggedIn();
}
else if($sessionHandler->getSessionVariable('RoleId')==2) {
  UtilityManager::ifTeacherNotLoggedIn();        
}
UtilityManager::headerNoCache();
//require_once(BL_PATH . "/Moodle/init.php"); 

//THIS FILE IS INCLUDED AFTER HEADER FILE AS SOME CALCULATIONS ARE DONE  AND SESSION VARIABLES ARE SET IN MENU FILE WHICH
//IS REQUIRED FOR FEEDBACK MODULES
//require_once(BL_PATH . "/Student/initDashboard.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Home </title>
<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Moodle/moodleLoginForm.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<script language="javascript">
    window.onload=function(){enableTooltips()};
    submitForm();          
    function submitForm() {
       if("<?php echo $moodleURL; ?>" == "") {
          //alert("Moodle Link not defined"); 
       }
       else {
          return document.moodleLoginForm.submit();
       }
    }
</script>
</script>
</body>
</html>