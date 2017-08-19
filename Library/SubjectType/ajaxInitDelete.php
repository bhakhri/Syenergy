<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Arvind Singh Rawat
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MODULE','SubjectTypesMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['subjectTypeId']) || trim($REQUEST_DATA['subjectTypeId']) == '') {
        $errorMessage = 'Invalid subject Type';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
        $subjectTypeManager =  SubjectTypeManager::getInstance();
        
        $foundArray = SubjectTypeManager::getInstance()->getCheckSubject(" WHERE subjectTypeId = '".$REQUEST_DATA['subjectTypeId']."'");
        if($foundArray[0]['cnt']==0) { 
           if($subjectTypeManager->deleteSubjectType($REQUEST_DATA['subjectTypeId'])) {
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
//*****************  Version 3  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Library/SubjectType
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/SubjectType
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectType
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:44a
//Updated in $/Leap/Source/Library/SubjectType
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:46p
//Created in $/Leap/Source/Library/SubjectType
//added two new files
//1) ajaxInitDelete.php which performs delete function through ajax
//caaling
//2) ajaxInitList function which performs ajax table listing
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>

