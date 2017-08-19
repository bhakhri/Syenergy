<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit Period Slot Detail  
//
//
// Author : Jaineesh
// Created on : (15.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodSlotMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true); 
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['slotName']) || trim($REQUEST_DATA['slotName']) == '') {
        $errorMessage .=  ENTER_SLOT_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['slotAbbr']) || trim($REQUEST_DATA['slotAbbr']) == '')) {
        $errorMessage .= ENTER_SLOT_Abbr."\n";  
    }
	

    if (trim($errorMessage) == '') {
		if(trim($REQUEST_DATA['isActive'])==1){ 
			
        require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
        $foundArray = PeriodSlotManager::getInstance()->getPeriodSlot(' WHERE  LCASE(slotName)="'.add_slashes(trim(strtolower($REQUEST_DATA['slotName']))).'" AND periodSlotId!='.$REQUEST_DATA['periodSlotId']);
        if(trim($foundArray[0]['slotName'])=='') {  //DUPLICATE CHECK
			$foundArray1 = PeriodSlotManager::getInstance()->getPeriodSlot(' WHERE  UCASE(slotAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['slotAbbr']))).'" AND periodSlotId!='.$REQUEST_DATA['periodSlotId']);
			if(trim($foundArray1[0]['slotAbbr'])=='') {  //DUPLICATE CHECK
            $returnStatus = PeriodSlotManager::getInstance()->editPeriodSlot($REQUEST_DATA['periodSlotId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
			else {
                      $periodSlotId=$REQUEST_DATA['periodSlotId'];   
                      $activePeriodSlotArray=PeriodSlotManager::getInstance()->makeAllPeriodSlotInActive(" AND ps.periodSlotId !=".$periodSlotId); //make previous entries inactive
                      echo SUCCESS;           
                }
                
        }
        else {
           echo PERIOD_SLOT_ALREADY_EXIST;
        }
    }
	else {
			echo PERIOD_SLOTNAME_ALREADY_EXIST;
        }
    }
	else {
		
		require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
		$activeCheckArray = PeriodSlotManager::getInstance()->getPeriodSlot(' WHERE isActive=1 AND periodSlotId!='.$REQUEST_DATA['periodSlotId']);
		if(trim($activeCheckArray[0]['isActive'])!='') {  //DUPLICATE CHECK
		 //die('deactive');
			$foundArray = PeriodSlotManager::getInstance()->getPeriodSlot(' WHERE  LCASE(slotName)="'.add_slashes(trim(strtolower($REQUEST_DATA['slotName']))).'" AND periodSlotId!='.$REQUEST_DATA['periodSlotId']);
			if(trim($foundArray[0]['slotName'])=='') {  //DUPLICATE CHECK
				$foundArray1 = PeriodSlotManager::getInstance()->getPeriodSlot(' WHERE  UCASE(slotAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['slotAbbr']))).'" AND periodSlotId!='.$REQUEST_DATA['periodSlotId']);
				if(trim($foundArray1[0]['slotAbbr'])=='') {  //DUPLICATE CHECK
				$returnStatus = PeriodSlotManager::getInstance()->editPeriodSlot($REQUEST_DATA['periodSlotId']);
				if($returnStatus === false) {
					$errorMessage = FAILURE;
				}
				else {
						  echo SUCCESS;           
					}
        }
        else {
           echo PERIOD_SLOT_ALREADY_EXIST;
        }
    }
	else {
			echo PERIOD_SLOTNAME_ALREADY_EXIST;
        }
	}
	 else { 
		 //die('deactive');
          echo ACTIVE_PERIOD_SLOT_UPDATE;
         }
	}
	}
    else {
        echo $errorMessage;
    }
// $History: ajaxInitPeriodSlotEdit.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:33p
//Created in $/LeapCC/Library/PeriodSlot
//to get the file add, delete & edit records
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:15p
//Created in $/Leap/Source/Library/PeriodSlot
//used the file during edit record
//
?>