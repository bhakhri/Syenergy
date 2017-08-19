<?php
//-------------------------------------------------------
// Purpose: To store the records of Grade in array from the database, pagination and search, delete 
// functionality
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GradeSetMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GradeSetManager.inc.php");
    $gradeSetManager = GradeSetManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $str = ""; 
       if(stristr('yes',add_slashes($REQUEST_DATA['searchbox']))) {
          $str = ' OR isActive = 1 ';
       }
       else if(stristr('no',add_slashes($REQUEST_DATA['searchbox']))) {
          $str = ' OR isActive = 0 ';
       }
       $filter = ' WHERE (gradeSetName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$str.')';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'gradeSetName';
    
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $gradeSetManager->getGradeSetList($filter);   
    $cnt1 = count($totalArray);
    
    $gradeRecordArray = $gradeSetManager->getGradeSetList($filter,$limit,$orderBy);
    $cnt = count($gradeRecordArray);
    
    $gradeTypeArray = array( 0=>'No',1=>'Yes');
	  for($j=0;$j<$cnt;$j++) {
	   $gradeType = $gradeRecordArray[$j]['isActive'];
	   
	   $gradeArray = explode(',', $gradeType);
	   $str = '';
	   foreach ($gradeArray as $rec) {
		   if (!empty($str)) {
			   $str .= ',';
		   }
		   $str .= $gradeTypeArray[$rec];
	   }

   }

    for($i=0;$i<$cnt;$i++) {
        // add GradeId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $gradeRecordArray[$i]['gradeSetId'] , 'srNo' => ($records+$i+1) ),$gradeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 


?>
