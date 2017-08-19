<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");                

require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
$fbMgr=FeedBackTeacherMappingManager::getInstance();

define('MODULE','ADVFB_AssignSurveyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
                         
    $feedBackTypeFlag = add_slashes(trim($REQUEST_DATA['feedBackTypeFlag']));   
    $timeTableLabelId = add_slashes(trim($REQUEST_DATA['timeTableLabelId']));
    $catId = add_slashes(trim($REQUEST_DATA['catId']));
    $labelId = add_slashes(trim($REQUEST_DATA['labelId']));   
    
    $insertString = '';   
    
    if($feedBackTypeFlag==4) {  
          $insertString = '';
          
          $surveyId = $labelId;
          $condition = " AND tt.timeTableLabelId = '$timeTableLabelId'";
          $foundTeacherArray = FeedBackAssignSurveyAdvancedManager::getInstance()->getFetchTimeTableEmployee($condition);
          if(is_array($foundTeacherArray) && count($foundTeacherArray)>0 ) {
             for($i=0; $i<count($foundTeacherArray); $i++) { 
                $tTimeTableLabelId = $foundTeacherArray[$i]['timeTableLabelId'];
                $tClassId = $foundTeacherArray[$i]['classId'];
                $tGroupId = $foundTeacherArray[$i]['groupId'];
                $tSubjectId = $foundTeacherArray[$i]['subjectId'];
                $tEmployeeId = $foundTeacherArray[$i]['employeeId'];              
                
                $conditionMap = "timeTableLabelId = '$tTimeTableLabelId' AND feedbackSurveyId = '$surveyId' 
                                 AND classId = '$tClassId' AND groupId = '$tGroupId' 
                                 AND subjectId = '$tSubjectId' AND employeeId = '$tEmployeeId' ";
                $foundTeacherMapArray = FeedBackAssignSurveyAdvancedManager::getInstance()->getCheckTeacherMapping($conditionMap);
                if(is_array($foundTeacherMapArray) && count($foundTeacherMapArray)==0 ) { 
                   if($insertString!='') {
                     $insertString .= ",";  
                   }
                   $insertString .=" ($tTimeTableLabelId,$surveyId, $tClassId, $tGroupId, $tSubjectId, $tEmployeeId) ";
                }
             }
          }
          
          if($insertString!='') {
             if(SystemDatabaseManager::getInstance()->startTransaction()) {     
                $ret=$fbMgr->doTeacherMapping($insertString); 
                if($ret==false){
                  echo 0;
                  die;
                }
                if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                    
                }
                else {
                  echo 0;   
                  die;
                }
             }
          }
    }
    
    if($timeTableLabelId != '' and $catId != '') {
        $subjectTypeId=-1;
    
        //fetch subject type from category table
        $foundArray1 = FeedBackAssignSurveyAdvancedManager::getInstance()->getCategoryRelation(' WHERE fc.feedbackCategoryId="'.$catId.'"' );
        if(is_array($foundArray1) && count($foundArray1)>0){
          $subjectTypeId=$foundArray1[0]['subjectTypeId'];
        }
        else{
            echo 0;
            die;
        }
    
        //fetch classes
        $condition = ' AND ttc.timeTableLabelId="'.$timeTableLabelId.'" AND s.subjectTypeId="'.$subjectTypeId.'"';
        //$foundArray2 = FeedBackAssignSurveyAdvancedManager::getInstance()->getSubjectTypeClasses($condition);
        $foundArray2 = FeedBackAssignSurveyAdvancedManager::getInstance()->getAllSubjectTypeClasses($condition);
        if(is_array($foundArray2) && count($foundArray2)>0 ) {
          echo json_encode($foundArray2);
          die;
        } 
        else {
          echo 0;
          die;
        }
    }
// $History: ajaxGetSubjectTypeClass.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>