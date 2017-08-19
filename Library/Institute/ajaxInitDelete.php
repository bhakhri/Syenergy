<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InstituteMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['instituteId']) || trim($REQUEST_DATA['instituteId']) == '') {
        $errorMessage = 'Invalid Institute';
    }
    //applied check : user cannot delete the institute through he/she loged in the system
    if($sessionHandler->getSessionVariable('InstituteId')==trim($REQUEST_DATA['instituteId'])){
        echo 'You cannot delete the institute through which you hava logged in';
        die;
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        $instituteManager =  InstituteManager::getInstance();
        
        //as  "class","batch" and "employee" table depends on "institute" table
        $recordArray1 = $instituteManager->checkInClass($REQUEST_DATA['instituteId']);
        $recordArray2 = $instituteManager->checkInBatch($REQUEST_DATA['instituteId']);
        $recordArray3 = $instituteManager->checkInEmployee($REQUEST_DATA['instituteId']);
        
        if($recordArray1[0]['found']==0 && $recordArray2[0]['found']==0 && $recordArray3[0]['found']==0) {
            // delete logo -- by Pushpender
            $logoArray = $instituteManager->checkLogoName($REQUEST_DATA['instituteId']);
            if(UtilityManager::notEmpty($logoArray[0]['instituteLogo'])) {
                if(file_exists(IMG_PATH.'/Institutes/'.$logoArray[0]['instituteLogo'])) {
                    @unlink(IMG_PATH.'/Institutes/'.$logoArray[0]['instituteLogo']);
                  }
            }
            // delete record
            if($instituteManager->deleteInstitute($REQUEST_DATA['instituteId']) ) {
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
//User: Dipanjan     Date: 16/07/09   Time: 10:35
//Updated in $/LeapCC/Library/Institute
//Added the check : user cannot delete the institute through which he/she
//have logged in the system
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Institute
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Library/Institute
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:30p
//Updated in $/Leap/Source/Library/Institute
//Added dependency constraint check
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/07/08    Time: 8:14p
//Updated in $/Leap/Source/Library/Institute
//added the code to delete image logo
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Library/Institute
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 11:45a
//Created in $/Leap/Source/Library/Institute
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 11:41a
//Updated in $/Leap/Source/Library/University
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 10:27a
//Created in $/Leap/Source/Library/University
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan   Date: 6/25/08    Time: 11:31 a
//Updated in $/Leap/Source/Library/City
//added code to delete city
//
?>

