<?php
//-------------------------------------------------------
// Purpose: To delete UNIBERSITY detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UniversityMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['universityId']) || trim($REQUEST_DATA['universityId']) == '') {
        $errorMessage = 'Invalid University';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/UniversityManager.inc.php");
        $universityManager =  UniversityManager::getInstance();
        
        //as  "class" table depends on "university" table
        $recordArray = $universityManager->checkInClass($REQUEST_DATA['universityId']);
        if($recordArray[0]['found']==0) {
            // delete logo -- by Pushpender
            $logoArray = $universityManager->checkLogoName($REQUEST_DATA['universityId']);
            if(UtilityManager::notEmpty($logoArray[0]['universityLogo'])) {
                if(file_exists(IMG_PATH.'/University/'.$logoArray[0]['universityLogo'])) {
                    @unlink(IMG_PATH.'/University/'.$logoArray[0]['universityLogo']);
                  }
            }
            // delete record
            if($universityManager->deleteUniversity($REQUEST_DATA['universityId']) ) {
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
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/University
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:16a
//Updated in $/Leap/Source/Library/University
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:29p
//Updated in $/Leap/Source/Library/University
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Library/University
//Added Image upload functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:09a
//Created in $/Leap/Source/Library/University
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/07/08    Time: 8:14p
//Updated in $/Leap/Source/Library/University
//added the code to delete image logo
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Library/University
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 11:45a
//Created in $/Leap/Source/Library/University
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

