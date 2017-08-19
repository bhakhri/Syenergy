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
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackManager = FeedBackManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = 'LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;
    //////
    
    /////search functionility not needed   
    $filter=" AND ffl.feedbackSurveyId=".$REQUEST_DATA['surveyId'];
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestion';
    
     $orderBy = " $sortField $sortOrderBy";   
     //echo  $orderBy;

    ////////////
    
    $totalArray          = $feedBackManager->getTotalQuestions($filter);
    $feedBackRecordArray = $feedBackManager->getQuestionsList($filter,$limit,$orderBy);
    $cnt = count($feedBackRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(
                             array(
                                    'srNo' => ($records+$i+1),
                                    "questions" => "<input type=\"checkbox\" name=\"questions\" id=\"questions\" value=\"".$feedBackRecordArray[$i]['feedbackQuestionId'] ."\">")
        , $feedBackRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxQuestionsList.php $
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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/04/09   Time: 17:58
//Updated in $/Leap/Source/Library/FeedBack
//Fixed paging bugs
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:44
//Created in $/Leap/Source/Library/FeedBack
//Created "Copy Survey" Module
?>
