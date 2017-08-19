<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "city" TABLE AND DELETION AND PAGING
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
    $appDataManager = AppraisalDataManager::getInstance();
    
    $showHODAppraisal=0; //whether to show/hide appraisal done by HOD to employees or not.
    if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
      $employeeId=$sessionHandler->getSessionVariable('EmployeeToBeAppraised');
      $showHODAppraisal=1;
    }
    else{
      $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
      if($sessionHandler->getSessionVariable('SHOW_HOD_APPRAISAL')==1){
          $showHODAppraisal=1;
      }
      else{
          $showHODAppraisal=0;
      }
    }
    
   
    $appraisalDataArray=$appDataManager->getAppraisalFormData($employeeId);
    $appraisalDataCount=count($appraisalDataArray);
    
    $appraisalProofTextArray=$appDataManager->getAppraisalProofText();
    $aptCount=count($appraisalProofTextArray);
    $proofTextArray=array();
    for($i=0;$i<$aptCount;$i++){
        $proofTextArray[$appraisalProofTextArray[$i]['appraisalTabId']]=$appraisalProofTextArray[$i]['appraisalProofText'];
    }
    
    if($appraisalDataCount>0){
        //count unique tabs
        $appraisalTabArray=array_values(array_unique(explode(',',(UtilityManager::makeCSList($appraisalDataArray,'appraisalTabId')))));
        $appraisalTabCount=count($appraisalTabArray);
        
        //calculate MAX of each title
        $appraisalTttleArray=array_values(array_unique(explode(',',(UtilityManager::makeCSList($appraisalDataArray,'appraisalTitleId')))));
        $appraisalTitleCount=count($appraisalTttleArray);
        $appraisalTitleMaxArray=array();
        
        for($x=0;$x<$appraisalTitleCount;$x++){
            for($y=0;$y<$appraisalDataCount;$y++){
                if($appraisalDataArray[$y]['appraisalTitleId']==$appraisalTttleArray[$x]['appraisalTitleId']){
                   $appraisalTitleMaxArray[$appraisalDataArray[$y]['appraisalTitleId']] += $appraisalDataArray[$y]['appraisalWeightage'];
                }        
            }
        }
    }
    
    
    
    
    //fetch data about leaves
    $appraisalLeavaDataArray=$appDataManager->getAppraisalLeaveData($employeeId);
    
    if($showHODAppraisal==1){
     //fetch data about reviewer
     $appraisalReviewerDataArray=$appDataManager->getAppraisalReviewerData($employeeId);
     //initialize them with 0 if not data found
     $appraisalReviewerDataArray[0]['initiative']=trim($appraisalReviewerDataArray[0]['initiative'])==''?0:trim($appraisalReviewerDataArray[0]['initiative']);
     $appraisalReviewerDataArray[0]['responsibility']=trim($appraisalReviewerDataArray[0]['responsibility'])==''?0:trim($appraisalReviewerDataArray[0]['responsibility']);
     $appraisalReviewerDataArray[0]['punctuality']=trim($appraisalReviewerDataArray[0]['punctuality'])==''?0:trim($appraisalReviewerDataArray[0]['punctuality']);
     $appraisalReviewerDataArray[0]['committment']=trim($appraisalReviewerDataArray[0]['committment'])==''?0:trim($appraisalReviewerDataArray[0]['committment']);
     $appraisalReviewerDataArray[0]['loyality']=trim($appraisalReviewerDataArray[0]['loyality'])==''?0:trim($appraisalReviewerDataArray[0]['loyality']);
     $appraisalReviewerDataArray[0]['develop']=trim($appraisalReviewerDataArray[0]['develop'])==''?0:trim($appraisalReviewerDataArray[0]['develop']);
     $appraisalReviewerDataArray[0]['oral']=trim($appraisalReviewerDataArray[0]['oral'])==''?0:trim($appraisalReviewerDataArray[0]['oral']);
     $appraisalReviewerDataArray[0]['written_com']=trim($appraisalReviewerDataArray[0]['written_com'])==''?0:trim($appraisalReviewerDataArray[0]['written_com']);
     $appraisalReviewerDataArray[0]['teamwork']=trim($appraisalReviewerDataArray[0]['teamwork'])==''?0:trim($appraisalReviewerDataArray[0]['teamwork']);
     $appraisalReviewerDataArray[0]['leadership']=trim($appraisalReviewerDataArray[0]['leadership'])==''?0:trim($appraisalReviewerDataArray[0]['leadership']);
     $appraisalReviewerDataArray[0]['relation']=trim($appraisalReviewerDataArray[0]['relation'])==''?0:trim($appraisalReviewerDataArray[0]['relation']);
     $appraisalReviewerDataArray[0]['matuarity']=trim($appraisalReviewerDataArray[0]['matuarity'])==''?0:trim($appraisalReviewerDataArray[0]['matuarity']);
     $appraisalReviewerDataArray[0]['temper']=trim($appraisalReviewerDataArray[0]['temper'])==''?0:trim($appraisalReviewerDataArray[0]['temper']);
     $appraisalReviewerDataArray[0]['rel_stud']=trim($appraisalReviewerDataArray[0]['rel_stud'])==''?0:trim($appraisalReviewerDataArray[0]['rel_stud']);
     $appraisalReviewerDataArray[0]['grandtotal']=trim($appraisalReviewerDataArray[0]['grandtotal'])==''?0:trim($appraisalReviewerDataArray[0]['grandtotal']);
    }
    
    
    
    
    
// $History: initList.php $
?>