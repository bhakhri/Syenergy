<?php
//-------------------------------------------------------
// Purpose: To delete a Offense Detail
//
// Author : Jaineesh
// Created on : (22.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OffenseMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true); 
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['offenseId']) || trim($REQUEST_DATA['offenseId']) == '') {
        $errorMessage = 'Invalid Offense';
    }
    if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/OffenseManager.inc.php");
		$foundArray = OffenseManager::getInstance()->getOffenseExisting(' AND o.offenseId="'.$REQUEST_DATA['offenseId'].'"');
			if(is_array($foundArray) && count($foundArray)>0 ) {  
				echo DEPENDENCY_CONSTRAINT;
	}
	else {
		$offenseManager =  OffenseManager::getInstance();
			 if($offenseManager->deleteOffense($REQUEST_DATA['offenseId'])) {
             echo DELETE;
			 }
    }
	}
   else {
        echo $errorMessage;
    }


	 
// $History: ajaxInitOffenseDelete.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/25/08   Time: 12:37p
//Updated in $/LeapCC/Library/Offense
//modified for data constraint
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:41p
//Created in $/LeapCC/Library/Offense
//ajax files for add, edit or delete
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:14p
//Created in $/Leap/Source/Library/Offense
//ajax files to get offense detail, add, edit or delete
//

?>