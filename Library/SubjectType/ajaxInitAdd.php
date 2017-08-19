<?php 

//
//  This File calls addFunction used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectTypesMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
    if (!isset($REQUEST_DATA['subjectTypeCode']) || trim($REQUEST_DATA['subjectTypeCode']) == '') {
        $errorMessage .= ENTER_SUBJECT_TYPE_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectTypeName']) || trim($REQUEST_DATA['subjectTypeName']) == '')) {
        $errorMessage .= ENTER_SUBJECT_TYPE_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityId']) || trim($REQUEST_DATA['universityId']) == '')) {
        $errorMessage .= SELECT_UNIVERSITY."\n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
        
        $foundArray = SubjectTypeManager::getInstance()->getSubjectType(' WHERE UCASE(subjectTypeName)="'.add_slashes(strtoupper($REQUEST_DATA['subjectTypeName'])).'" AND universityId = "'.$REQUEST_DATA['universityId'].'"');
        if(trim($foundArray[0]['subjectTypeCode'])=='') {  //DUPLICATE CHECK
            $foundArray = SubjectTypeManager::getInstance()->getSubjectType(' WHERE UCASE(subjectTypeCode)="'.add_slashes(strtoupper($REQUEST_DATA['subjectTypeCode'])).'" AND universityId = "'.$REQUEST_DATA['universityId'].'"');
            if(trim($foundArray[0]['subjectTypeCode'])=='') {  //DUPLICATE CHECK
                $returnStatus = SubjectTypeManager::getInstance()->addSubjectType();
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
                else {
                    echo SUCCESS;           
                }
            }
            else {
                  echo SUBJECT_TYPE_ABBR_ALREADY_EXIST;      
            }
        }
        else {
           echo SUBJECT_TYPE_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
 

//$History: ajaxInitAdd.php $
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
//*****************  Version 3  *****************
//User: Arvind       Date: 9/09/08    Time: 7:27p
//Updated in $/Leap/Source/Library/SubjectType
//added common messages
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/26/08    Time: 5:19p
//Updated in $/Leap/Source/Library/SubjectType
//not done any change
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:25p
//Created in $/Leap/Source/Library/SubjectType
//new files added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Country
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Country
//New Files Added in Country Folder

?>
