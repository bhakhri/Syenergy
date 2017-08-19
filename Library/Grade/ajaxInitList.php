<?php
//-------------------------------------------------------
// Purpose: To store the records of Grade in array from the database, pagination and search, delete 
// functionality
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GradeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GradeManager.inc.php");
    $gradeManager = GradeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE gradeSetName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          gradeLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          failGrade LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          gradeStatus LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          gradePoints LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'gradeLabel';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $gradeManager->getTotalGrade($filter);
    $gradeRecordArray = $gradeManager->getGradeList($filter,$limit,$orderBy);
	
    $cnt = count($gradeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        // add GradeId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $gradeRecordArray[$i]['gradeId'] , 
                                        'srNo' => ($records+$i+1) ),$gradeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 


?>
