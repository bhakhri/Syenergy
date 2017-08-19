<?php
//-------------------------------------------------------
// Purpose: To delete country detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CountryMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['rollId']) || trim($REQUEST_DATA['rollId']) == '') {

        $errorMessage = 'Session Expired! Please Login Again.';
    }
    
    $rollNo=($REQUEST_DATA['rollId']);
    $rollIdArray=explode(",",$rollNo);
   
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BlockStudentManager.inc.php");
        $StuManager =  BlockStudentManager::getInstance();
        
        // Checks dependency constraint
       for($i=0;$i<count($rollIdArray);$i++){
        $recordArray = $StuManager->checkInState($rollIdArray[$i]);
        if($recordArray[0]['found']==1) {
            if($StuManager->deleteStudent($rollIdArray[$i]) ) {
         	
            }
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
     echo DELETE;
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Country
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/05/08   Time: 4:42p
//Updated in $/Leap/Source/Library/Country
//define MODULE, ACCESS - added
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/25/08    Time: 11:54a
//Created in $/Leap/Source/Library/Country
//added new file which is used for deleting a record through ajax
?>

