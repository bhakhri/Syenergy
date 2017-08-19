<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit Period Slot Detail  
//
//
// Author : Jaineesh
// Created on : (15.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OffenseMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true); 
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['offenseName']) || trim($REQUEST_DATA['offenseName']) == '') {
       $errorMessage .=  ENTER_OFFENSE_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['offenseAbbr']) || trim($REQUEST_DATA['offenseAbbr']) == '')) {
        $errorMessage .= ENTER_OFFENSE_ABBR."\n";  
    }
	

    if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/OffenseManager.inc.php");
		$foundArray = OffenseManager::getInstance()->getOffense(' WHERE LCASE(o.offenseName)="'.add_slashes(trim(strtolower($REQUEST_DATA['offenseName']))).'" AND o.offenseId!='.$REQUEST_DATA['offenseId']);
        if(trim($foundArray[0]['offenseName'])=='') {  //DUPLICATE CHECK
			$foundArray1 = OffenseManager::getInstance()->getOffense(' WHERE UCASE(o.offenseAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['offenseAbbr']))).'" AND o.offenseId!='.$REQUEST_DATA['offenseId']);
			
			if(trim($foundArray1[0]['offenseAbbr'])=='') {  //DUPLICATE CHECK
            $returnStatus = OffenseManager::getInstance()->editOffense($REQUEST_DATA['offenseId']);

            if($returnStatus === false) {
                echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
		}
  	else {
            echo OFFENSE_ALREADY_EXIST;
        }
	 }
	else {
			echo OFFENSENAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitOffenseEdit.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Library/Offense
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:15p
//Created in $/Leap/Source/Library/PeriodSlot
//used the file during edit record
//
?>