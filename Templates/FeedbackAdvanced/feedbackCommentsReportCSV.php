 <?php 
//--------------------------------------------------------------------
// This file is used as CSV version for display feedback comments
// Author :Dipanjan Bhattacharjee
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CityMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbManager = FeedBackReportAdvancedManager::getInstance();
    
     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    $classId          = trim($REQUEST_DATA['classId']);
    $labelId          = trim($REQUEST_DATA['labelId']);
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    $subjectId        = trim($REQUEST_DATA['subjectId']);
    
    if($labelId=='' or $timeTableLabelId==''){
        echo 'Required Pamameters Missing';
        die;
    }
    
    //check type of label.if it is of "subject",then classes can be fetched otherwise not
    $typeArray=$fbManager->getSurveyLabelType($labelId);
    
    if($typeArray[0]['cnt']!=0){
      if($classId==''){
         echo 'Required Pamameters Missing';
         die;
      }    
    }
    
    if($classId!=''){
      $filter=' AND f.feedbackSurveyId='.$labelId.' AND f.classId='.$classId.' AND fs.timeTableLabelId='.$timeTableLabelId;
    }
    else{
      $filter=' AND f.feedbackSurveyId='.$labelId.' AND fs.timeTableLabelId='.$timeTableLabelId;  
    }
    
    if($subjectId!=''){
        $filter .=' AND f.subjectId='.$subjectId;
    }
    $filter .=" AND trim(f.comments)!=''";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
     if($classId!=''){ 
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
     }
     else{
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';   
     }
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    /*
     if($classId!=''){
      $fbRecordArray = $fbManager->getFeedbackCommentsList($filter,' ',$orderBy);
     }
     else{
      $fbRecordArray = $fbManager->getFeedbackCommentsFromEmployeesList($filter,' ',$orderBy);
     }
    */
    $fbRecordArray = $fbManager->getFeedbackCommentsFromEmployeesList($filter,' ',$orderBy);
    $cnt = count($fbRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if(trim($fbRecordArray[$i]['comments'])==''){
           $fbRecordArray[$i]['comments']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['className'])==''){
            $fbRecordArray[$i]['className']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['subjectCode'])==''){
            $fbRecordArray[$i]['subjectCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['employeeName'])==''){
            $fbRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$fbRecordArray[$i]);
    }
    
    $csvData = '';
    if($classId!=''){
      $csvData .= "#, Class, Subject, Employee, Comments \n";
    }
    else{
      $csvData .= "#, Label, Category, Comments \n";  
    }
    foreach($valueArray as $record) {
       if($classId!=''){ 
         $csvData .= $record['srNo'].', '.parseCSVComments($record['className']).', '.parseCSVComments($record['subjectCode']).', '.parseCSVComments($record['employeeName']).','.parseCSVComments($record['comments']);
       }
       else{
         $csvData .= $record['srNo'].', '.parseCSVComments($record['feedbackSurveyLabel']).', '.parseCSVComments($record['feedbackCategoryName']).','.parseCSVComments($record['comments']);  
       }
        $csvData .= "\n";
    }
	if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
    
ob_end_clean();
header("Cache-Control: public, must-revalidate");
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
header('Content-Disposition: attachment; filename="feedbackCommentsReport.csv"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>