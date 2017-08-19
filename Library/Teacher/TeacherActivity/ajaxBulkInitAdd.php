<?php
//-------------------------------------------------------
// Purpose: To add course topic
//
// Author : Rajeev Aggarwal
// Created on : 24.01.2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifTeacherNotLoggedIn(true);  
UtilityManager::headerNoCache();

    $errorMessage ='';
    
    if (!isset($REQUEST_DATA['studentCourse']) || trim($REQUEST_DATA['studentCourse']) == '') {
        $errorMessage .= SELECT_SUBJECT."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['courseTopic']) || trim($REQUEST_DATA['courseTopic']) == '')) {
        $errorMessage .= ENTER_SUBJECT_TOPIC."\n";
    }
     
  
    if (trim($errorMessage) == '') {
        
		require_once(MODEL_PATH . "/SubjectTopicManager.inc.php");   
        $chkStatus=0;
        $foundArray1 = SubjectTopicManager::getInstance()->getSubjectTopic(' AND st.subjectId='.$REQUEST_DATA['studentCourse'].' AND UCASE(st.topic)="'.add_slashes(strtoupper(trim($REQUEST_DATA['courseTopic']))).'"');
        if(trim($foundArray1[0]['topic'])=='') {  //DUPLICATE CHECK

			 $returnStatus = SubjectTopicManager::getInstance()->addBulkCourseTopic($REQUEST_DATA['subjectTopicId']);
			 if($returnStatus === false) {
				
				echo SUBJECT_TOPIC_ALREADY_EXIST ;
			 }
			 elseif($returnStatus === "greaterValue") {
				
				echo SUBJECT_TOPIC_GREATER_VALUE;
			 }
			 elseif($returnStatus === "emptyValue") {
				
				echo SUBJECT_TOPIC_EMPTY_VALUE;
			 }
			 
			 else {
             
				echo SUCCESS;           
			 }
        }else{
       
			echo SUBJECT_TOPIC_ALREADY_EXIST ;  
        }
  }        
  else {
     echo $errorMessage;
  }
  
// $History: ajaxBulkInitAdd.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/13/10    Time: 1:04p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/13/10    Time: 12:14p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Library/SubjectTopic
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/30/09    Time: 4:15p
//Updated in $/LeapCC/Library/SubjectTopic
//updated role permission
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectTopic
//added define variable for Role Permission
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/12/09    Time: 3:14p
//Created in $/LeapCC/Library/SubjectTopic
//bulksubject topic file added
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 2/11/09    Time: 12:27p
//Updated in $/Leap/Source/Library/ScCourseTopic
//Updated validations and fixed bugs
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/02/09    Time: 12:16p
//Updated in $/Leap/Source/Library/ScCourseTopic
//added validations and removed bugs
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/24/09    Time: 2:42p
//Created in $/Leap/Source/Library/ScCourseTopic
//Intial checkin
?>