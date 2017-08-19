<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CopySurveyMaster');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackManager = FeedBackManager::getInstance();

    /////////////////////////
      
    $surveyCategory=$REQUEST_DATA['categoryId'];
    $sourceId=$REQUEST_DATA['sourceSurveyId'];
    $targetId=$REQUEST_DATA['targetSurveyId'];
    $questionIds=$REQUEST_DATA['questionIds'];
    $copyOptionValue=$REQUEST_DATA['copyOptionValue'];
    
	if($copyOptionValue){

		$optionArr=$feedBackManager->checkOptionExists($targetId);
		if($optionArr[0]['totalRecords']==0){
		
			$ret3=$feedBackManager->copySurveyOption($sourceId,$targetId);
		}
	}
	//check unique questions
    $ret1=$feedBackManager->checkUniqueQuestions($sourceId,$targetId,$questionIds);
    
    $uniqueIds            = UtilityManager::makeCSList($ret1,'feedbackQuestionId');
    $uniqueIdCount        = count(explode(',',$uniqueIds));
    $uniqueQuestions      = UtilityManager::makeCSList($ret1,'feedbackQuestion','~#~#~');
    $uniqueQuestions      = explode('~#~#~',$uniqueQuestions);
    $uniqueQuestionsCount = count($uniqueQuestions);
    $qstr='';
    
    if($uniqueIds!=''){
        $ret2=$feedBackManager->copyQuestions($uniqueIds,$targetId);
        if($ret2===true){
           for($i=0;$i<$uniqueQuestionsCount;$i++){
               if($qstr!=''){
                   $qstr .='~#~';
               }
               $qstr .=$uniqueQuestions[$i];
           }
           echo SUCCESS.'!@!'.$qstr;
           die;
        }
        else{
            echo FAILURE;
            die;
        }
    }
    else{
       echo "No questions are copied";
       die;
    }
// for VSS
// $History: ajaxCopyQuestions.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/FeedBack
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Created in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/19/09    Time: 10:47a
//Updated in $/Leap/Source/Library/FeedBack
//Added functionality to add question options(grades) along with feedback
//question
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:44
//Created in $/Leap/Source/Library/FeedBack
//Created "Copy Survey" Module
?>
