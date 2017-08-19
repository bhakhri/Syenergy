<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT DESIGNATION 
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramScaleMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['histogramRangeFrom']) || trim($REQUEST_DATA['histogramRangeFrom']) == '')) {
        $errorMessage .= ENTER_HISTOGRAM_RANGEFROM. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['histogramRangeTo']) || trim($REQUEST_DATA['histogramRangeTo']) == '')) {
        $errorMessage .= ENTER_HISTOGRAM_RANGETO. '<br/>';
    }
	
	if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HistogramScaleManager.inc.php");
		$histogramScaleManager = HistogramScaleManager::getInstance();

		$histogramRangeFrom = add_slashes($REQUEST_DATA['histogramRangeFrom']);
		$histogramRangeTo = add_slashes($REQUEST_DATA['histogramRangeTo']);
		$histogramId = add_slashes($REQUEST_DATA['histogramLabel']);
		$histogramScaleId = add_slashes($REQUEST_DATA['histogramScaleId']);

		$conditions = "WHERE					histogramId = $histogramId 
						AND						(
													($histogramRangeFrom BETWEEN histogramRangeFrom AND histogramRangeTo) 
																OR
													($histogramRangeTo BETWEEN histogramRangeFrom AND histogramRangeTo)
												)
						AND						histogramScaleId != $histogramScaleId";
		
		$foundArray = $histogramScaleManager->getHistogramScale($conditions);
		if(trim($foundArray[0]['histogramScaleId'])=='') {
			$returnStatus = HistogramScaleManager::getInstance()->editHistogramScale($REQUEST_DATA['histogramScaleId']);
			if($returnStatus === false) {
				echo FAILURE;
			}
			else {
				echo SUCCESS;           
			}
		}
		else {
			echo HISTOGRAMRANGEFROM_ALREADY_EXIST;
		}
	}
    else {
        echo $errorMessage;
    }



// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramScale
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:39p
//Updated in $/Leap/Source/Library/HistogramScale
//add define access in module
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:14a
//Updated in $/Leap/Source/Library/HistogramScale
//modified
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:09a
//Updated in $/Leap/Source/Library/HistogramScale
//modified in code
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:25p
//Created in $/Leap/Source/Library/HistogramScale
//used for edit
//
?>