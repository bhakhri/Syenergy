<?php
//-------------------------------------------------------
// Purpose: To Add block table data
//
// Author : Dipanjan Bhattacharjee
// Created on : (11.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BlockCourse');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['blockName']) || trim($REQUEST_DATA['blockName']) == '') {
        $errorMessage .= ENTER_BLOCK_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['abbreviation']) || trim($REQUEST_DATA['abbreviation']) == '')) {
        $errorMessage .= ENTER_BLOCK_ABBR."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['buildingId']) || trim($REQUEST_DATA['buildingId']) == '')) {
        $errorMessage .= SELECT_BUILDING."\n";    
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BlockManager.inc.php");
        $foundArray = BlockManager::getInstance()->getBlock(' WHERE ( UCASE(blockName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['blockName']))).'" OR UCASE(abbreviation)="'.add_slashes(strtoupper(trim($REQUEST_DATA['abbreviation']))).'" ) AND buildingId='.$REQUEST_DATA['buildingId']);
        if(trim($foundArray[0]['blockName'])=='') {  //DUPLICATE CHECK
            $returnStatus = BlockManager::getInstance()->addBlock();
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            if(strtoupper(trim($foundArray[0]['blockName']))==strtoupper(trim($REQUEST_DATA['blockName']))){
               echo BLOCK_ALREADY_EXIST;
               die;
            }
            if(strtoupper(trim($foundArray[0]['abbreviation']))==strtoupper(trim($REQUEST_DATA['abbreviation']))){
               echo BLOCK_ABBR_ALREADY_EXIST;
               die;
            }
            echo BLOCK_ALREADY_EXIST; //if all fails
            die;
        }
    }
    else {
        echo $errorMessage;
    }
    
    
// $History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/08/09    Time: 11:27
//Updated in $/LeapCC/Library/Block
//Done bug fixing.
//bug ids---
//0000919 to 0000922
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:39
//Updated in $/LeapCC/Library/Block
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Block
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:41p
//Updated in $/Leap/Source/Library/Block
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/20/08    Time: 6:52p
//Updated in $/Leap/Source/Library/Block
//Added Standard Messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:43p
//Updated in $/Leap/Source/Library/Block
//Created "Block" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:05p
//Created in $/Leap/Source/Library/Block
//Initial Checkin
?>

