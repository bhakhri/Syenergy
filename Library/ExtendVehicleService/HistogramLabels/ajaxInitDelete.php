<?php
//-------------------------------------------------------
// Purpose: To delete histogramlabel detail
//
// Author : Jaineesh
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramLabelMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['histogramId']) || trim($REQUEST_DATA['histogramId']) == '') {
        $errorMessage = INVALID_HISTOGRAMLABEL;
    }



	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/HistogramScaleManager.inc.php");
		$histogramScaleManager =  HistogramScaleManager::getInstance();
		$foundArray = $histogramScaleManager -> getHistogramScale('WHERE histogramId = "'.$REQUEST_DATA['histogramId'].'"');
		$histogramId = $foundArray[0]['histogramId'];

		require_once(MODEL_PATH . "/HistogramLabelManager.inc.php");
		$histogramLabelManager =  HistogramLabelManager::getInstance();
				
		if ($histogramId != $REQUEST_DATA['histogramId']) {
			if($histogramLabelManager->deleteHistogramLabel($REQUEST_DATA['histogramId']) ) {
				echo DELETE;
			}
			else {
				echo DEPENDENCY_CONSTRAINT;
			}
		}
		else {
			//echo $errorMessage;
			echo ('Cannot be deleted due to dependency constraints');
		}
	}
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramLabels
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:40p
//Updated in $/Leap/Source/Library/HistogramLabels
//add define access in module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/24/08   Time: 7:08p
//Updated in $/Leap/Source/Library/HistogramLabels
//modified
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:23p
//Created in $/Leap/Source/Library/HistogramLabels
//use to delete histogram labels
//

?>