<?php
//-------------------------------------------------------
// Purpose: To update states table data
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$mod = $REQUEST_DATA['mod'];
define('MODULE',$mod);
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
     
     $errorMessage ='';
     
    if (!isset($REQUEST_DATA['studentSubject']) || trim($REQUEST_DATA['studentSubject']) == '') {
        $errorMessage .= SELECT_SUBJECT."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectTopic']) || trim($REQUEST_DATA['subjectTopic']) == '')) {
        $errorMessage .= ENTER_SUBJECT_TOPIC."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectAbbr']) || trim($REQUEST_DATA['subjectAbbr']) == '')) {
        $errorMessage .= ENTER_SUBJECT_TOPIC_ABBREVATION."\n";
    }      
    
     if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SubjectTopicManager.inc.php");
      
         $chkStatus=0;

            $foundArray = SubjectTopicManager::getInstance()->getSubjectTopic(' AND st.subjectId="'.$REQUEST_DATA['studentSubject'].'" AND UCASE(st.topic)="'.add_slashes(strtoupper(trim($REQUEST_DATA['subjectTopic']))).'" AND st.subjectTopicId!='.$REQUEST_DATA['subjectTopicId'] );
        if(trim($foundArray[0]['topic'])!='') {  //DUPLICATE CHECK
           $chkStatus=1;
           echo SUBJECT_TOPIC_ALREADY_EXIST  ;  
        }

      
      if($chkStatus==0) {  
            $foundArray1 = SubjectTopicManager::getInstance()->getSubjectTopic(' AND st.subjectId="'.$REQUEST_DATA['studentSubject'].'" AND UCASE(st.topicAbbr)="'.add_slashes(strtoupper(trim($REQUEST_DATA['subjectAbbr']))).'" AND st.subjectTopicId!='.$REQUEST_DATA['subjectTopicId']);
        if(trim($foundArray1[0]['topic'])!='') {  //DUPLICATE CHECK
           $chkStatus=1;
           echo "This abbr. already exists." ;  
        }
      }
      
      if($chkStatus==0)  {
          $returnStatus = SubjectTopicManager::getInstance()->editSubjectTopic($REQUEST_DATA['subjectTopicId']);
          if($returnStatus === false) {
            $errorMessage = FAILURE;
          }
          else {
             echo SUCCESS;           
          }
      }    
  }        
  else {
     echo $errorMessage;
  }
      
// $History: ajaxInitEdit.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/30/09    Time: 4:15p
//Updated in $/LeapCC/Library/SubjectTopic
//updated role permission
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectTopic
//added define variable for Role Permission
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/28/09    Time: 11:51a
//Updated in $/LeapCC/Library/SubjectTopic
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/16/09    Time: 6:13p
//Updated in $/LeapCC/Library/SubjectTopic
//CONDITION UPDATE
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/16/09    Time: 4:28p
//Updated in $/LeapCC/Library/SubjectTopic
//condition update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectTopic
//subject topic file added
//

?>