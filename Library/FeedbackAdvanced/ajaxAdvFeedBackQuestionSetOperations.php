<?php
//------------------------------------------------------------------
// THIS FILE IS USED TO ADD/EDIT/DELETE AN ADV. FEEDBACK CATEGORY
// Author : Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
$opMode=trim($REQUEST_DATA['modeName']);
if($opMode==1){
  $opName='add';
}
else if($opMode==2){
  $opName='edit';  
}
else if($opMode==3){
  $opName='delete';  
}
else{
  echo TECHNICAL_PROBLEM;
  die;
}
define('MODULE','ADVFB_QuestionSet');
define('ACCESS',$opName);

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if($opMode==2){//if it is EDIT operation
        if ($errorMessage == '' && (!isset($REQUEST_DATA['setName']) || trim($REQUEST_DATA['setName']) == '')) {
            $errorMessage .= ENTER_ADV_QUESTION_SET_NAME."\n";  
        }
        $setName=add_slashes(trim($REQUEST_DATA['setName']));
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackQuestionSetAdvancedManager.inc.php");
        $fbMgr=FeedBackQuestionSetAdvancedManager::getInstance();
        if($opMode==2 or $opMode==3){
            $setId=trim($REQUEST_DATA['setId']);
            if($setId==''){
                echo 'Invalid Question Set';
                die;
            }
        }
        
        //************for addition*********
        if($opMode==1){
            $instituteId=$sessionHandler->getSessionVariable('InstituteId');
            
            $setNameArray=$REQUEST_DATA['setName'];
            $setNameCount=count($setNameArray);
            $setNameString='';
            $insertString='';
            if($setNameCount==0){
                echo ENTER_ADV_QUESTION_SET_NAME;
                die;
            }
            for($i=0;$i<$setNameCount;$i++){
                if($setNameString!=''){
                    $setNameString .=' , ';
                    $insertString  .= ' , ';
                }
                $setNameString .= '"'.add_slashes(trim(strtoupper($setNameArray[$i]))).'"';
                $insertString  .= " ('".add_slashes(trim($setNameArray[$i]))."' , $instituteId ) ";
            }
            
            //check for duplicate values
            $foundArray1=$fbMgr->getFeedbackQuestionSet(' AND UCASE(feedbackQuestionSetName) IN ('.$setNameString.') ');
            if($foundArray1[0]['feedbackQuestionSetName']!=''){
                echo ADV_QUESTION_SET_ALREADY_EXIST;
                die;
            }
            
            if(SystemDatabaseManager::getInstance()->startTransaction()) {
              //now add the QUESTION SET
              if($insertString==''){
                  echo  ENTER_ADV_QUESTION_SET_NAME;
                  die;
              }
              $returnValue=$fbMgr->addAdvFeedbackQuestionSetMultiple($insertString);
              
              if($returnValue==false){
                  echo FAILURE;
                  die;
              }
              
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                echo SUCCESS;
                die;
              }
              else {
                echo FAILURE;
                die;
              }
            }
           else {
              echo FAILURE;
              die;
           }

          /*  
            //now add the QUESTION SET
            $returnValue=$fbMgr->addAdvFeedbackQuestionSet($setName);
            if($returnValue==true){
                echo SUCCESS;
                die;
            }
            else{
                echo FAILURE;
                die;
            }
          */  
        }
        //************addition operation ends*********
        
        
        //************for editing*********
        if($opMode==2){
            
            //check for duplicate values
            $foundArray1=$fbMgr->getFeedbackQuestionSet(' AND ( UCASE(feedbackQuestionSetName)="'.strtoupper($setName).'" ) AND feedbackQuestionSetId!='.$setId);
            if($foundArray1[0]['feedbackQuestionSetName']!=''){
                echo ADV_QUESTION_SET_ALREADY_EXIST;
                die;
            }
            
            //check for usage of this questionSetId in different tables.if used then do no allow to edit or delete
            $usageFlag=$fbMgr->getQuestionSetUsage($setId);
            if($usageFlag==1){
                echo DEPENDENCY_CONSTRAINT_EDIT;
                die;
            }
            
            //now edit the QUESTION SET
            $returnValue=$fbMgr->editAdvFeedbackQuestionSet($setId,$setName);
            if($returnValue==true){
                echo SUCCESS;
                die;
            }
            else{
                echo FAILURE;
                die;
            }
        }
        //************editing operation ends*********
        
        //************for deleting*********
        if($opMode==3){
            
            //check for usage of this questionSetId in different tables.if used then do no allow to edit or delete
            $usageFlag=$fbMgr->getQuestionSetUsage($setId);
            if($usageFlag==1){
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
            
            //now delete the question set
            $returnValue=$fbMgr->deleteQuestionSet($setId);
            if($returnValue==true){
                echo DELETE;
                die;
            }
            else{
                echo FAILURE;
                die;
            }
            
        }
        //************for deleting*********
       
       //if add/edit/delete operation fails
        echo TECHNICAL_PROBLEM;
        die; 
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxAdvFeedBackQuestionSetOperations.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 18/02/10   Time: 18:30
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified UI design: Now users can add multiple records at a time.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:34
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Added checks during edit operation.If this question set is used ,then
//it can not be edited.
?>