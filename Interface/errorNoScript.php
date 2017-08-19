<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Java script disabled  </title>

<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>
 
</head>
<body>
<?php 
    //require_once(TEMPLATES_PATH . "/header.php");
	echo NO_SCRIPT_ERROR;
    //require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
