<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");
    
    global $sessionHandler; 
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    
    $studentManager = StudentRegistration::getInstance(); 
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    if($roleId=='2') {
      UtilityManager::ifTeacherNotLoggedIn();
    }
    else {
      UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache();
 
    $studentId = $REQUEST_DATA['studentId'];
    
    if($studentId=='') {
      $studentId=0;  
    }
  
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'commentDate';
    $orderBy = " $sortField $sortOrderBy";      
    
    
    $json_val = '';
    $totalArray = $studentManager->getMentorCommentsCount($studentId);
    $mentorRecordArray = $studentManager->getMentorCommentsList($studentId,$orderBy,$limit);
    $cnt = count($mentorRecordArray);
    for($i=0;$i<$cnt;$i++) {
        // add bankId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),
                                        $mentorRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
