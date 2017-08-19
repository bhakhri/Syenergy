<?php
//-------------------------------------------------------
// Purpose: To delete testtype detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypesMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['testTypeId']) || trim($REQUEST_DATA['testTypeId']) == '') {
        $errorMessage = 'Invalid TestType';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TestTypeManager.inc.php");
        $testtypeManager =  TestTypeManager::getInstance();
        $recordArray = $testtypeManager->checkInTest($REQUEST_DATA['testTypeId']);
        if($recordArray[0]['found']==0) {
            if($testtypeManager->deleteTestType($REQUEST_DATA['testTypeId']) ) {
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
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Library/TestType
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:32p
//Updated in $/Leap/Source/Library/TestType
//Added dependency constraint check
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/09/08    Time: 7:18p
//Updated in $/Leap/Source/Library/TestType
//Add `Select` as default selected value in dropdowns of University,
//Degree, Branch, Study Period, Evaluation Criteria, subject and subject
//type.
//and made modifications so that data is  being populated in study period
//dropdown
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 7:08p
//Updated in $/Leap/Source/Library/TestType
//Added AjaxEnabled Delete functionality
//
?>

