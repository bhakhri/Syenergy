<?php
//-------------------------------------------------------
//  This File is used for fetching class for 
//
//
// Author :Jaineesh
// Created on : 07.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$labelId = trim($REQUEST_DATA['labelId']);
    if($labelId==''){
        echo 'Required Parameter Missing';
        die;
    }
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
	$dateArray = $studentReportsManager->getSelectedTimeTableDates(' AND timeTableLabelId='.$labelId);
    if(is_array($dateArray) and count($dateArray)>0){
        if($dateArray[0]['startDate']=='' or $dateArray[0]['startDate']=='0000-00-00'){
            $dateArray[0]['startDate']='';
        }
        if($dateArray[0]['endDate']=='' or $dateArray[0]['endDate']=='0000-00-00'){
            $dateArray[0]['endDate']='';
        }
	    echo json_encode($dateArray[0]);
    }

// $History: initGetClasses.php $
?>