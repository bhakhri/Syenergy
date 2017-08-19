<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW HISTOGRAM LABELS
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramLabelMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['histogramLabel']) || trim($REQUEST_DATA['histogramLabel']) == '')) {
        $errorMessage .= ENTER_HISTOGRAMLABEL_NAME. '<br/>';
    }
        
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HistogramLabelManager.inc.php");
		$foundArray = HistogramLabelManager::getInstance()->getHistogramLabel(' WHERE UCASE(histogramLabel)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['histogramLabel']))).'"');
		if(trim($foundArray[0]['histogramLabel'])=='') {  //DUPLICATE CHECK
			$returnStatus = HistogramLabelManager::getInstance()->addHistogramLabel();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				echo HISTOGRAMLABEL_ALREADY_EXIST;
			}
	}
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HistogramLabels
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:40p
//Updated in $/Leap/Source/Library/HistogramLabels
//add define access in module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:22p
//Created in $/Leap/Source/Library/HistogramLabels
//contains code for adding data in histogram labels
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:16p
//Updated in $/Leap/Source/Library/Designation
//modified in indentation
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:42p
//Updated in $/Leap/Source/Library/Designation
//modified in messages
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/20/08    Time: 2:32p
//Updated in $/Leap/Source/Library/Designation
//modified for error message
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/11/08    Time: 12:42p
//Updated in $/Leap/Source/Library/Designation
//modified for duplicate record check
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:21p
//Updated in $/Leap/Source/Library/Designation
//change errormessage from echo
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 2:24p
//Updated in $/Leap/Source/Library/Designation
?>