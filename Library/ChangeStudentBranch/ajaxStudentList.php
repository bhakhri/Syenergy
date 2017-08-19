<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ChangeStudentBranch');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ChangeStudentBranchManager.inc.php");
    $studentManager = ChangeStudentBranchManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $classId=trim($REQUEST_DATA['classId']);
    if($classId==''){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
      die;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $filter=' AND c.classId='.$classId;
    
    //$totalArray = $studentManager->getTotalStudents($filter);
    $studentRecordArray = $studentManager->getStudentList($filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    if($cnt>0){
        //get the euivalent class with different branches
        $equiClassArray=$studentManager->getEquivalentClassesWithDifferentBrach($classId);
        $equiClassOptions ='<option value="">Select</option>';
        foreach($equiClassArray As $equi){
           $equiClassOptions .='<option value="'.$equi['classId'].'">'.$equi['className'].'</option>';
        }
    }
    
    for($i=0;$i<$cnt;$i++) {

       $studentRecordArray[$i]['newClassId']='<select name="newClass" id="'.$studentRecordArray[$i]['studentId'].'" class="inputbox" style="width:220px;" >'.$equiClassOptions.'</select>';
        
       $valueArray = array_merge(array( 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($studentRecordArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
?>