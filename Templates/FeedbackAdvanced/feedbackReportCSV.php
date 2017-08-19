<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
       
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbMgr=FeedBackReportAdvancedManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    
    global $sessionHandler;   
    
    $valueArray = array();  
    $valueArrayPoint = array();  
    
    $valueArray=$sessionHandler->getSessionVariable('IdToFeedbackScoreReport');   
    $valueArrayPoint=$sessionHandler->getSessionVariable('IdToFeedbackPointReport');   
    
    //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       $comments = str_ireplace('<b>', "", $comments); 
       $comments = str_ireplace('</b>', "", $comments); 
       if(eregi(",", $comments) or eregi("\n", $comments)) {
          return '"'.$comments.'"'; 
       } 
       else {
          return $comments; 
       }
    }       

    $timeTableName=trim($REQUEST_DATA['timeTableName']);      
    $labelName=trim($REQUEST_DATA['labelName']);      
    $teacherName=trim($REQUEST_DATA['teacherName']);      
    $className=trim($REQUEST_DATA['className']);   
    $categoryName=trim($REQUEST_DATA['categoryName']);    
 
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $categoryId=trim($REQUEST_DATA['categoryId']);  
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($labelId=='') {
      $labelId=0;  
    }
    
    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'questionName';
    
    $orderBy = " $sortField $sortOrderBy";


    $condition = " WHERE feedbackadv_survey.timeTableLabelId = '$timeTableLabelId' AND 
                      feedbackadv_survey.feedbackSurveyId = '$labelId' AND feedbackadv_survey_mapping.roleId = '4' ";
                      
    if($classId!='' && $classId!='all') {
      $condition .= " AND feedbackadv_survey_mapping.classId = '$classId'";  
    }
    
    if($employeeId!='' && $employeeId!='all') {
      $condition .= " AND employee.employeeId = '$employeeId'";  
    }
    
    if($categoryId!='' && $categoryId!='all') {
      $condition .= " AND feedbackadv_survey_mapping.feedbackCategoryId = '$categoryId'";  
    }
    

    $csvData = '';   
    $csvData .= "Time Table,".parseCSVComments($timeTableName);
    $csvData .= "\nLabel,".parseCSVComments($labelName);
    
    if($teacherName!='All') {
      $csvData .= "\nTeacher Name,".parseCSVComments($teacherName);  
    }
    if($className!='All') {   
      $csvData .= "\nClass,".parseCSVComments($className);  
    } 
    if($categoryName!='All') {   
      $csvData .= "\nCategory,".parseCSVComments($categoryName);  
    }
    
    $csvData .= "\n";
    $csvData .= "#,Question";
    for($i=0;$i<count($valueArrayPoint);$i++) { 
       $point = $valueArrayPoint[$i];  
       $csvData .= ",".parseCSVComments($point); 
    }
    $csvData .= ",Weight Average,Response";
    $csvData .= "\n";
    
    $cnt = count($valueArray);    
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $csvData .= parseCSVComments($valueArray[$i]['srNo']).",".parseCSVComments($valueArray[$i]['questionName']);
       for($j=0;$j<count($valueArrayPoint);$j++) { 
         $pp = "p_".$j;
         $csvData .= ",".parseCSVComments($valueArray[$i][$pp]); 
       }
       $csvData .= ",".parseCSVComments($valueArray[$i]['avg']).",".parseCSVComments($valueArray[$i]['response']);
       $csvData .= "\n";
    }
    
    if($cnt==0){
        $csvData .=",,,".NO_DATA_FOUND;
    }

    UtilityManager::makeCSV($csvData,'FeedbackReport.csv');
die;    
?>