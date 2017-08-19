<?php
//------------------------------------------------------------------
// THIS FILE IS USED TO ADD/EDIT/DELETE AN ADV. FEEDBACK CATEGORY
// Author : Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
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
define('MODULE','ADVFB_CategoryMaster');
define('ACCESS',$opName);

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if($opMode!=3){//if it is not delete operation
        /*
        if (!isset($REQUEST_DATA['labelId']) || trim($REQUEST_DATA['labelId']) == '') {
            $errorMessage .=  SELECT_ADV_LABEL_NAME."\n"; 
        }
        */
        if ($errorMessage == '' && (!isset($REQUEST_DATA['catName']) || trim($REQUEST_DATA['catName']) == '')) {
            $errorMessage .= ENTER_ADV_CATEGORY_NAME."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['catRel']) || trim($REQUEST_DATA['catRel']) == '')) {
            $errorMessage .= ENTER_ADV_CATEGORY_RELN."\n";  
        }
       /* 
        if ($errorMessage == '' && (!isset($REQUEST_DATA['catDesc']) || trim($REQUEST_DATA['catDesc']) == '')) {
            $errorMessage .= ENTER_ADV_CATEGORY_DESC."\n";  
        }
       */ 
        if(trim($REQUEST_DATA['catRelation'])==4 and trim($REQUEST_DATA['subjectType'])==''){
            echo SELECT_ADV_CATEGORY_SUBJECT_TYPE;
            die;
        }
       // $labelId=trim($REQUEST_DATA['labelId']);
        $catName=add_slashes(trim($REQUEST_DATA['catName']));
        $parentCatId=trim($REQUEST_DATA['parentCatId']);
        $catReln=trim($REQUEST_DATA['catRel']);
        $subjectType=trim($REQUEST_DATA['subjectType']);
        $catDesc=add_slashes(trim($REQUEST_DATA['catDesc']));
        $printOrder=add_slashes(trim($REQUEST_DATA['printOrder']));
        $catComments=trim($REQUEST_DATA['catComments']);
        
        /*if($parentCatId!=''){
            if($catDesc==''){
              echo ENTER_ADV_CATEGORY_DESC;
              die;  
            }
        }*/

        //check with global feedback relationship array
        if(!array_key_exists(intval($catReln),$advFeedBackRelationship)){
            echo ADV_INVALID_CATEGORY_RELN;
            die;
        }
        
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackCategoryAdvancedManager.inc.php");
        $fbMgr=FeedBackCategoryAdvancedManager::getInstance();
        if($opMode==2 or $opMode==3){
            $catId=trim($REQUEST_DATA['catId']);
            if($catId==''){
                echo 'Invalid Category';
                die;
            }
        }
        
        //************for addition*********
        if($opMode==1){
            //check for duplicate values
            //$foundArray1=$fbMgr->getFeedbackCategory(' WHERE UCASE(feedbackCategoryName)="'.strtoupper($catName).'" AND  feedbackSurveyId='.$labelId);
            $foundArray1=$fbMgr->getFeedbackCategory(' WHERE UCASE(feedbackCategoryName)="'.strtoupper($catName).'"');
            if($foundArray1[0]['feedbackCategoryName']!=''){
                echo ADV_CATEGORY_ALREADY_EXIST;
                die;
            }
            
            //print order restriction [in a same levelId 2categories cannot have same printOrder]
            //$foundArray=$fbMgr->getPrintOrder(' WHERE feedbackSurveyId='.$labelId.' AND printOrder="'.$printOrder.'"');
            $foundArray=$fbMgr->getPrintOrder(' WHERE printOrder="'.$printOrder.'"');
            if($foundArray[0]['printOrder']!=''){
                echo ADV_SAME_PRINT_ORDER;
                die;
            }
            
            //check for 2level hierarchy
            if($parentCatId!=''){
             $foundArray2=$fbMgr->getParentCategory(' AND fc.feedbackCategoryId='.$parentCatId);
             if($foundArray2[0]['feedbackCategoryId']!=$parentCatId){
                 echo ADV_TWO_LEVEL_HIERARCHY_FOUND;
                 die;
             }
            }
            //now add the category
            //$returnValue=$fbMgr->addAdvFeedbackCategory($labelId,$catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments);
            $returnValue=$fbMgr->addAdvFeedbackCategory($catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments);
            if($returnValue==true){
                echo SUCCESS;
                die;
            }
            else{
                echo FAILURE;
                die;
            }
        }
        //************addition operation ends*********
        
        
        //************for editing*********
        if($opMode==2){
            
            //check for usage of this catId in different tables.if used then do no allow to edit or delete
            $usageFlag=$fbMgr->getCategoryUsage($catId);
            if($usageFlag==1){
                echo DEPENDENCY_CONSTRAINT_EDIT;
                die;
            }
            
            //check for duplicate values
            //$foundArray1=$fbMgr->getFeedbackCategory(' WHERE ( UCASE(feedbackCategoryName)="'.strtoupper($catName).'" AND  feedbackSurveyId='.$labelId.' ) AND feedbackCategoryId!='.$catId);
            $foundArray1=$fbMgr->getFeedbackCategory(' WHERE ( UCASE(feedbackCategoryName)="'.strtoupper($catName).'" ) AND feedbackCategoryId!='.$catId);
            if($foundArray1[0]['feedbackCategoryName']!=''){
                echo ADV_CATEGORY_ALREADY_EXIST;
                die;
            }
            
            //print order restriction [in a same levelId 2categories cannot have same printOrder]
            //$foundArray=$fbMgr->getPrintOrder(' WHERE feedbackSurveyId='.$labelId.' AND printOrder="'.$printOrder.'" AND feedbackCategoryId!='.$catId);
            $foundArray=$fbMgr->getPrintOrder(' WHERE printOrder="'.$printOrder.'" AND feedbackCategoryId!='.$catId);
            if($foundArray[0]['printOrder']!=''){
                echo ADV_SAME_PRINT_ORDER;
                die;
            }
            
            //check for 2level hierarchy
            if($parentCatId!=''){
             $foundArray2=$fbMgr->getParentCategory(' AND fc.feedbackCategoryId='.$parentCatId);
             if($foundArray2[0]['feedbackCategoryId']!=$parentCatId){
                 echo ADV_TWO_LEVEL_HIERARCHY_FOUND;
                 die;
             }
            }
            
            //check for self-parent
            if($catId==$parentCatId){
                echo ADV_SELF_PARENT_HIERARCHY_FOUND;
                die;
            }
            
            //now edit the category
            //$returnValue=$fbMgr->editAdvFeedbackCategory($catId,$labelId,$catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments);
            $returnValue=$fbMgr->editAdvFeedbackCategory($catId,$catName,$parentCatId,$catReln,$subjectType,$catDesc,$printOrder,$catComments);
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
            
            //check for usage of this catId in different tables.if used then do no allow to edit or delete
            $usageFlag=$fbMgr->getCategoryUsage($catId);
            if($usageFlag==1){
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
            
            //cannot delete parent check
            $parentArray=$fbMgr->checkParentCategory($catId);
            if($parentArray[0]['cnt']!=0){
                echo ADV_PARENT_DELETE_CHECK;
                die;
            }
            
            //now delete the category
            $returnValue=$fbMgr->deleteCategory($catId);
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
// $History: ajaxAdvFeedBackCategoryOperations.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/01/10    Time: 18:29
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated "Advanced Feedback Category" module as feedbackSurveyId is
//removed from table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:47
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created module "Advanced Feedback Category Module"
?>