<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
if(CURRENT_PROCESS_FOR=="sc") {
	require_once(UI_PATH . "/scIndexHome.php");
}
else {
	require_once(UI_PATH . "/ccIndexHome.php");
}
?>