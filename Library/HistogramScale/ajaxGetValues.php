<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE HISTOGRAM SCALE LIST
//
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramScaleMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['histogramScaleId'] ) != '') {
    require_once(MODEL_PATH . "/HistogramScaleManager.inc.php");
    $foundArray = HistogramScaleManager::getInstance()->getHistogramScale('WHERE histogramScaleId="'.$REQUEST_DATA['histogramScaleId'].'"');
	
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
}

// $History: ajaxGetValues.php $
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
//contains for listing histogram scale
//

?>