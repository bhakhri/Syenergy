<?php

//  This File calls Edit Function used in adding Grade Records
//-------------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/GradeManager.inc.php");
    $gradeManager = GradeManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['gradeId']) && $REQUEST_DATA['act']=='del') {
            
        if($recordArray[0]['found']==0) {
            if($gradeManager->deleteGrade($REQUEST_DATA['gradeId']) ) {
                $message = DELETE;
            }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (bk.gradeLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'% ")';
    }
    
    $totalArray = $gradeManager->getTotalGrade($filter);
    $gradeRecordArray = $gradeManager->getGradeList($filter,$limit);

?>