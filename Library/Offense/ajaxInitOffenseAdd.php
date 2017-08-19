<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A OFFENSE 
//
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
define('ACCESS','add');
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
		$foundArray = OffenseManager::getInstance()->getOffense(' WHERE  LCASE(offenseName)="'.add_slashes(trim(strtolower($REQUEST_DATA['offenseName']))).'"');
        if(trim($foundArray[0]['offenseName'])=='') {  //DUPLICATE CHECK
			$foundArray1 = OffenseManager::getInstance()->getOffense(' WHERE  UCASE(offenseAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['offenseAbbr']))).'"');
	
			if(trim($foundArray1[0]['offenseAbbr'])=='') {  //DUPLICATE CHECK
            $returnStatus = OffenseManager::getInstance()->addOffense();
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
// $History: ajaxInitOffenseAdd.php $
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