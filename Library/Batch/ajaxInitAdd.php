<?php
/*
  This File calls addFunction used in adding Batch Records

 Author :Arvind Singh Rawat
 Created on : 14-June-2008
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BatchMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['batchName']) || trim($REQUEST_DATA['batchName']) == '') {
        $errorMessage .= ENTER_BATCH_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['startDate']) || trim($REQUEST_DATA['startDate']) == '')) {
        $errorMessage .= ENTER_BATCH_START_DATE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['endDate']) || trim($REQUEST_DATA['endDate']) == '')) {
        $errorMessage .= ENTER_BATCH_END_DATE."\n";
    }
    
    
    global $sessionHandler;      
    $instituteId= $sessionHandler->getSessionVariable('InstituteId');
    
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BatchManager.inc.php");
        $foundArray = BatchManager::getInstance()->getBatch(' WHERE  instituteId="'.$instituteId .'" AND UCASE(batchName)="'.add_slashes(strtoupper($REQUEST_DATA['batchName'])).'"');
    if(trim($foundArray[0]['batchName'])=='') {  //DUPLICATE CHECK
            $returnStatus = BatchManager::getInstance()->addBatch();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;
            }
        }
        else {
            echo 'Batch Name already exists.';
        }             
    }
    else {
        echo $errorMessage;
    }
//$History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/16/10    Time: 12:41p
//Updated in $/LeapCC/Library/Batch
//instituteId check added
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/18/09    Time: 6:11p
//Updated in $/LeapCC/Library/Batch
//fixed bug no.s 
//411,604,406,409,395,393,407
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Batch
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/06/08   Time: 10:14a
//Updated in $/Leap/Source/Library/Batch
//Added the module, access
//
//*****************  Version 6  *****************
//User: Arvind       Date: 9/09/08    Time: 6:45p
//Updated in $/Leap/Source/Library/Batch
//ADDED COMMON MESSAGES
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/27/08    Time: 3:21p
//Updated in $/Leap/Source/Library/Batch
//removed spaces
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/23/08    Time: 11:14a
//Updated in $/Leap/Source/Library/Batch
//remove <br/> tags from validation
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/21/08    Time: 5:03p
//Updated in $/Leap/Source/Library/Batch
//added a new fields batchYear validation
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/07/08    Time: 5:27p
//Updated in $/Leap/Source/Library/Batch
//replaced batch code with batch name in echo funcion 
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:25p
//Created in $/Leap/Source/Library/Batch
//new files added
?>

