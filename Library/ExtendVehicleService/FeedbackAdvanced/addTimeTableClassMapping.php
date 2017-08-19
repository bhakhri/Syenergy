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
require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transactions
define('MODULE','ADVFB_TeacherMapping');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    $fbMgr=FeedBackTeacherMappingManager::getInstance();

    if($errorMessage == '' && (!isset($REQUEST_DATA['timeTableLabelId']) || trim($REQUEST_DATA['timeTableLabelId']) == '')) {
      echo SELECT_TIME_TABLE."\n";  
      die;
    }
    if($errorMessage == '' && (!isset($REQUEST_DATA['surveyId']) || trim($REQUEST_DATA['surveyId']) == '')) {
      echo SELECT_SURVEY."\n";  
      die;
    }
    if($errorMessage == '' && (!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '')) {
      echo SELECT_CLASS."\n"; 
      die; 
    }
        
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $surveyId=trim($REQUEST_DATA['surveyId']);	
    $classId=trim($REQUEST_DATA['classId']);
    
    if($classId=='') {
      $classId=0;  
    }
    $classArray = explode(',',$classId);
    
    
    $finalClass ='';
    $classAlreadyExist ='';
      
    $condition = " AND  fm.timeTableLabelId = '$timeTableLabelId' AND  fm.feedbackSurveyId = '$surveyId' AND  fm.classId IN ($classId) ";
    $classExistArray=$fbMgr->getCheckFeedbackClass($condition);     
    for($i=0;$i<count($classArray);$i++) {
       $socClassId = $classArray[$i];  
       $st=0;
       for($j=0;$j<count($classExistArray);$j++) {
          $tClassId = $classExistArray[$j]['classId']; 
          if($tClassId==$socClassId) {
            $st=1;  
            if($classAlreadyExist!='') {
              $classAlreadyExist .=","; 
            }
            $classAlreadyExist .= $classExistArray[$j]['className']; 
            break;  
          }
       } 
       if($st==0) {
          if($finalClass!='') {
            $finalClass .= ","; 
          }  
          $finalClass .= $socClassId;
       }
    }
    
    if($finalClass=='') {
      $finalClass=0;  
    }
    
    // Fetch Time Table
    $status = SUCCESS;
    $insertString = '';
    $condition = " AND tt.classId IN ($finalClass) AND tt.timeTableLabelId = '$timeTableLabelId' ";
    $timeTableArray=$fbMgr->getTimeTableClassList($condition);  
    
    if(count($timeTableArray) > 0 ) {
        for($i=0;$i<count($timeTableArray);$i++) {
           $groupId  =  $timeTableArray[$i]['groupId'];
           $subjectId =  $timeTableArray[$i]['subjectId'];
           $employeeId =  $timeTableArray[$i]['employeeId'];
           $classId =  $timeTableArray[$i]['classId'];
           if($insertString!='') {
             $insertString .=",";
           }   
           $insertString .=" ('$timeTableLabelId','$surveyId', '$classId', '$groupId', '$subjectId', '$employeeId' ) ";    
        }
    }
    else {
      $status = "Time Table doest not exist";  
    }
                          
    //************for addition*********
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        if($insertString!='') {
           $ret=$fbMgr->doTeacherMapping($insertString);
           if($ret==false){
             echo FAILURE;
             die;
           }     
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
           echo $status."!~~!~~!".$classAlreadyExist; 
           die;
        }
        else {
           echo FAILURE;
           die;
        }
    }

?>