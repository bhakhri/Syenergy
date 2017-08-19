<?php
//---------------------------------------------------------------------------------
// Purpose: To disply adv. question list
// Author : Dipanjan Bbhattacharjee
// Created on : (11.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_QuestionMappingMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackQuestionMappingAdvancedManager.inc.php");
    $fbMgr = FeedBackQuestionMappingAdvancedManager::getInstance();

    // to limit records per page
    /*
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    */
    //////
    /*As we will not show pagination*/
    $limit='';
	 $page = 1;



    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestion';

    $orderBy = " $sortField $sortOrderBy";

    ////////////

    if(trim($REQUEST_DATA['labelId'])=='' or trim($REQUEST_DATA[''])=='catId' or trim($REQUEST_DATA['questionSetId'])==''){
        echo 'Required Parameters Missing';
        die;
    }

    $filter =' AND ftq.feedbackSurveyId='.trim($REQUEST_DATA['labelId']).' AND ftq.feedbackCategoryId='.trim($REQUEST_DATA['catId']).' AND ftq.feedbackQuestionSetId='.trim($REQUEST_DATA['questionSetId']);
    $filter2=' AND fq.feedbackQuestionSetId='.trim($REQUEST_DATA['questionSetId']);
    $totalArray    = $fbMgr->getTotalFeedbackMappedQuestion($filter,$filter2);
    $fbRecordArray = $fbMgr->getFeedbackMappedQuestionList($filter,$filter2,$limit,$orderBy);
    $cnt = count($fbRecordArray);


    $questionUsedIdArray   = array();
    if(is_array($fbRecordArray) and count($fbRecordArray)>0){
        $questionUsedIds=UtilityManager::makeCSList($fbRecordArray,'feedbackToQuestionId');

        //fetch usage of this question
        $usageArray = $fbMgr->getQuestionUsageList($questionUsedIds);
        if(is_array($usageArray) and count($usageArray)>0){
         $questionUsedIdArray =explode(',',UtilityManager::makeCSList($usageArray,'feedbackToQuestionId'));
        }
    }

    for($i=0;$i<$cnt;$i++) {
       if($fbRecordArray[$i]['printOrder']==-1){
           $val=($i+1);
       }
       else{
           $val=$fbRecordArray[$i]['printOrder'];
       }

       $chkStr='&nbsp;'.NOT_APPLICABLE_STRING;
       $checked='';
       $disabled='disabled="disabled"';

       //if feedback corresponding to this feedback is not yet done,then this question can be edited/de-allocated
       if(!in_array($fbRecordArray[$i]['feedbackToQuestionId'],$questionUsedIdArray)){
         //if this is not allocated
         if($fbRecordArray[$i]['feedbackToQuestionId']==-1){
             $checked='';
             $disabled='disabled="disabled"';
         }
         else{
             $checked='checked="checked"';
             $disabled='';
         }

         $chkStr="<input $checked type=\"checkbox\" name=\"questionsList\" id=\"questionsList$i\" value=\"".$fbRecordArray[$i]['feedbackToQuestionId'].'!~!~!'.$fbRecordArray[$i]['feedbackQuestionId']."\" onclick=\"makePrintOrderToggle($i,this.checked)\">";

       }

       //make print order textboxes
       $fbRecordArray[$i]['printOrder']='<input '.$disabled.' type="text" name="printOrder" id="printOrder'.$i.'" style="width:40px;" class="inputbox" value="'.$val.'" />';

       $valueArray = array_merge(array('srNo' => ($records+$i+1),"questions" => $chkStr ),$fbRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxAdvFeedBackQuestionMappingList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/01/10   Time: 16:57
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Corrected query
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 10:54
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created module "Feedback Question Mapping (Advanced)"
?>