<?php
//-------------------------------------------------------
// Purpose: To delete periodicity detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/PeriodicityManager.inc.php");

define('MODULE','PeriodicityMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    if (!isset($REQUEST_DATA['periodicityId']) || trim($REQUEST_DATA['periodicityId']) == '') {
        $errorMessage = 'Invalid periodicity';
    }
    
    $condition = " WHERE periodicityId = '".$REQUEST_DATA['periodicityId']."'"; 
    if (trim($errorMessage) == '') {
        $periodicityManager =  PeriodicityManager::getInstance();
        
        $foundArray = PeriodicityManager::getInstance()->getStudyPeriod($condition);
        if(trim($foundArray[0]['totalRecords'])=='0') {  //DUPLICATE CHECK
            if($periodicityManager->deletePeriodicity($REQUEST_DATA['periodicityId']) ) {
              echo DELETE;
            }
            else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
        else {
           echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/21/09    Time: 4:03p
//Updated in $/LeapCC/Library/Periodicity
//issue fix (557, 559-564) format & validation checks updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Periodicity
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/05/08   Time: 5:17p
//Updated in $/Leap/Source/Library/Periodicity
//added module,access 
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:42a
//Updated in $/Leap/Source/Library/Periodicity
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:40p
//Created in $/Leap/Source/Library/Periodicity
//added two new files 
//1)ajaxInitList.php which performs table listing funcion through ajax
//2) ajaxInitDelete.php which performs delete funciton through ajax
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/25/08    Time: 11:54a
//Created in $/Leap/Source/Library/Country
//added new file which is used for deleting a record through ajax
?>

