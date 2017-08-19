<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the student exam marks
//
// Author : Dipanjan Bbhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');  //for having last insert of a new "test"
    
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();
    
    $testMarksIds =split("," , $REQUEST_DATA['testMarksId']);
    $studentIds =split("," , $REQUEST_DATA['studentIds']);
    $classIds =split("," , $REQUEST_DATA['classIds']);
    $memc =split("," , $REQUEST_DATA['memofclass']); 
    $present =split("," , $REQUEST_DATA['present']);
    $marks =split("," , $REQUEST_DATA['marks']);
    
    //********creates or edits the test********
    $testId=0;
    if($REQUEST_DATA['testId']=="NT"){ //if new test is to be created
       $returnStatus = $teacherManager->addTest();
       $testId=SystemDatabaseManager::getInstance()->lastInsertId(); 
    }
    else{
       $returnStatus = $teacherManager->editTest($REQUEST_DATA['testId']);
       $testId=$REQUEST_DATA['testId'];
    }
    if($returnStatus === false) {
        echo FAILURE;      //if test can not be created or edited no further operation will happen
        exit(0);
    }
    //********creates or edits the test(ends)********
    
    $attCount=count($testMarksIds);  //count $testMarksIds
        
    $insQuery="";
   
    $errorMessage=SUCCESS;
  
  //started insert and update operations
  if($attCount > 0 && is_array($testMarksIds)){
    for($i=0; $i <$attCount; $i++ ){
        if($testMarksIds[$i]=="-1"){ 
         //these values are not in test_marks table.so insert them
          //create multiple insert query
           if($insQuery==""){
              $insQuery="($testId,$classIds[$i],$studentIds[$i],$REQUEST_DATA[subjectId],$REQUEST_DATA[maxMarks],$marks[$i],$present[$i],$memc[$i])";
           }
          else{
              $insQuery=$insQuery." ,($testId,$classIds[$i],$studentIds[$i],$REQUEST_DATA[subjectId],$REQUEST_DATA[maxMarks],$marks[$i],$present[$i],$memc[$i])";
          }
        }
       else{
          //these values are already in test_marks table.so update them [update funtion will be called multiple times]
           $returnStatus=$teacherManager->editTestMarks($testMarksIds[$i],$REQUEST_DATA['maxMarks'],$marks[$i],$present[$i],$memc[$i]);
           if($returnStatus === false) {  //tracking error in update query
                $errorMessage = FAILURE;
            }
       }     
        
    }

    //insert query will be execute one time but multiple insertions will be done
     if($insQuery!=""){  //check if insertion query should be performed or not
      $returnStatus=$teacherManager->addTestMarks($insQuery) ;
      if($returnStatus === false) {  //tracking error in insert query  
                $errorMessage = FAILURE;
       }
     }  
     
  }
//ended insert and update operations   
  
 echo   $errorMessage; 
// for VSS
// $History: scAjaxEnterMarks.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 10/03/08   Time: 5:31p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Modified getLastInserId() to lastInsertId() function in ajax files
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
