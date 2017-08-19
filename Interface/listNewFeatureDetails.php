<?php
ob_start();
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$host = "192.168.1.8";

$fileDownloadPath = 'http://'.$host . '/testing/coder/upload/';
$conn = mysql_connect('192.168.1.11','trainee','trainee') or die('could not find host');
mysql_select_db('trainee_broadcast_feature') or die('could not connect to database');
require_once(MODEL_PATH . "/BroadcastFeatureManager.inc.php");

	?> 

	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo SITE_NAME;?>: New Feature </title>
	<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
	//echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
	echo UtilityManager::includeJS("content_down.js"); 
	?> 
	 
		<?php
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/NewFeature/listNewFeatureDetailsContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
		?>
	</body>
</html>
</head>
<body>						
							
	

    





