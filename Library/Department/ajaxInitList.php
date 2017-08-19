<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (20.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DepartmentMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DepartmentManager.inc.php");
    $departmentManager = DepartmentManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND (departmentName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR abbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR employeeCount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';          
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'departmentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $departmentManager->getTotalDepartment($filter);
    $departmentRecordArray = $departmentManager->getDepartmentList($filter,$limit,$orderBy);
    $cnt = count($departmentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $departmentRecordArray[$i]['departmentId'] , 'srNo' => ($records+$i+1) ),$departmentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Department
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Library/Department
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Department
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:50p
//Created in $/Leap/Source/Library/Department
//used for add paging, sorting  in department table
//

  
?>
