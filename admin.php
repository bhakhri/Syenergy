<?php
	global $FE; require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	redirectBrowser(UtilityManager::buildTargetURL('index.php', Array('lang'=>$_GET['lang']), true));
?> 

// $History: admin.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC
//
//*****************  Version 2  *****************
//User: Kabir        Date: 12/06/08   Time: 1:11p
//Updated in $/Leap/Source
//added the history tag for version control
