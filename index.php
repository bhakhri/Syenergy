<?php

	global $FE;

	require_once($FE . "/Library/common.inc.php");

	require_once(BL_PATH . "/UtilityManager.inc.php");
	$url = UtilityManager::buildTargetURL('index.php', NULL);
	redirectBrowser($url);
?>

// $History: index.php $