<?php  
// Purpose: To store the records of states in array from the database, pagination and search, delete 
//
// Author : Arvind Singh Rawat
// Created on : (28.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BlockStudent');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/BlockStudentManager.inc.php");
    $blockStudentManager =BlockStudentManager::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	
    $filter='';
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = '  AND (CONCAT(IFNULL(b.firstName,"")," ",IFNULL(b.lastName,"")) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        IF(a.isStatus=1,"Blocked","'.NOT_APPLICABLE_STRING.'") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                        rollNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                        message LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%") ';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";         

	
    $totalArray = $blockStudentManager->getTotalStudent($filter);
    $studentRecordArray = $blockStudentManager->getStudentList($filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $id = $studentRecordArray[$i]['blockId'];	    
        $action1 = '<a href="" name="bubble" onclick="deleteSubject('.$id.');return false;" >
                    <img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Unblock Student" title="Unblock Student"></a>';
        $check="";
     	$span1='';
        $span2='';
        $checkall = "$span1<input type='checkbox' class='inputbox3' name='chb[]' id='chk_".$id."'value='".$id."' $check>$span2";
        $valueArray = array_merge(array('action1' => $action1,
        				                'srNo' => ($records+$i+1) ,
		                			    'checkAll' => $checkall ),
					                    $studentRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
