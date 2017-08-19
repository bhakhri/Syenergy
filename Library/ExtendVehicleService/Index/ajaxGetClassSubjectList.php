<?php
//-------------------------------------------------------
// Purpose: To get values of timetable from the database
//
// Author : Rajeev Aggarwal
// Created on : (31.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    UtilityManager::headerNoCache();
    define('MODULE','COMMON');
    define('ACCESS','view');

                
    
    $tableData1 = '';
    $condition='';
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    $orderBy = " $sortField $sortOrderBy";     
    
    
    $condition="";
    
    $timeTableLabelId = add_slashes($REQUEST_DATA['timeTableLabelId']);  
    $classId = add_slashes($REQUEST_DATA['classId']);
    $id =$sessionHandler->getSessionVariable('RoleId');
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($classId=='') {
      $classId=0;  
    }
    
    if($id==1 || $id==5) {      // Admin
       UtilityManager::ifNotLoggedIn(true);
       
    }
    if($id==2) {          // Teacher
       UtilityManager::ifTeacherNotLoggedIn(true);  
     /*
       $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
       $condition = " AND tt.timeTableLabelId='".$timeTableLabelId."' AND tt.employeeId = '".$employeeId."'"; 
       if($classId!='all') {
         $condition .= " AND c.classId='".$classId."'";  
       }
       $classSubjectArray = CommonQueryManager::getInstance()->getTeacherTimeTableSubject($condition);
       $totalRecord = count($classSubjectArray); 
    
       $classSubjectArray1 = CommonQueryManager::getInstance()->getTeacherTimeTableSubject($condition,$orderBy,$limit);
       $cnt = count($classSubjectArray1);
      */ 
    }
   
    if($classId!='all') {
     $condition = " AND c.classId='".$classId."'";  
    }
    $condition .= " AND ttc.timeTableLabelId='".$timeTableLabelId."'";
   
    $classSubjectArray = CommonQueryManager::getInstance()->getClassSubjectsList($condition);
    $totalRecord = count($classSubjectArray); 

    $classSubjectArray1 = CommonQueryManager::getInstance()->getClassSubjectsList($condition,$orderBy,$limit);
    $cnt = count($classSubjectArray1);
    
    for($i=0;$i<$cnt;$i++) {   
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),
                                       $classSubjectArray1[$i]); 
       if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
 
// $History: ajaxGetClassSubjectList.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/19/10    Time: 4:29p
//Updated in $/LeapCC/Library/Index
//condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/16/10    Time: 10:17a
//Updated in $/LeapCC/Library/Index
//sorting format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/10    Time: 5:15p
//Created in $/LeapCC/Library/Index
//initial checkin
//

?>