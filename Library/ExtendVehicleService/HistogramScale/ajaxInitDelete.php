<?php
//-------------------------------------------------------
// Purpose: To delete histogramscale detail
//
// Author : Jaineesh
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramScaleMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if (!isset($REQUEST_DATA['histogramScaleId']) || trim($REQUEST_DATA['histogramScaleId']) == '') {
	$errorMessage = INVALID_HISTOGRAMSCALE;
}
if (trim($errorMessage) == '') {
	require_once(MODEL_PATH . "/HistogramScaleManager.inc.php");
	$histogramScaleManager =  HistogramScaleManager::getInstance();
	if($histogramScaleManager->deleteHistogramScale($REQUEST_DATA['histogramScaleId']) ) {
		echo DELETE;
	}
	else {
		echo DEPENDENCY_CONSTRAINT;
	}
}
else {
	echo $errorMessage;
}
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramScale
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:39p
//Updated in $/Leap/Source/Library/HistogramScale
//add define access in module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:25p
//Created in $/Leap/Source/Library/HistogramScale
//used for delete records
//

?>