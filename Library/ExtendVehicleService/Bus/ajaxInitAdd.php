<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A BUSSTOP 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusCourse');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['busName']) || trim($REQUEST_DATA['busName']) == '') {
        $errorMessage .= ENTER_STOP_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= ENTER_BUS_NO."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['manYear']) || trim($REQUEST_DATA['manYear']) == '')) {
        $errorMessage .= SELECT_MAN_YEAR."\n";    
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusManager.inc.php");
        $foundArray = BusManager::getInstance()->getBus(' WHERE UCASE(busName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['busName']))).'" OR UCASE(busNo)="'.add_slashes(trim(strtoupper($REQUEST_DATA['busNo']))).'"');
        if(trim($foundArray[0]['busName'])=='') {  //DUPLICATE CHECK
            $returnStatus = BusManager::getInstance()->addBus();
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
                $sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
                echo SUCCESS ;           
            }
        }
        else {
          if(trim($foundArray[0]['busName'])==trim($REQUEST_DATA['busName'])){  
            echo BUS_ALREADY_EXIST;
          }
         elseif(trim($foundArray[0]['busNo'])==trim($REQUEST_DATA['busNo'])){
             echo  BUS_NO_ALREADY_EXIST;
         }
         else{
             echo BUS_ALREADY_EXIST; 
         } 
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Library/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Library/Bus
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:37
//Updated in $/SnS/Library/Bus
//Enhanced bus master module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Library/Bus
//Created Bus Master Module
?>