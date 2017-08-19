<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','RoleMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();
    $roleId=trim($REQUEST_DATA['roleId']);
    if($roleId==''){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":0","page":"'.$page.'","info" : ['.$json_val.']}';   
      die;
    }
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    $orderBy = " $sortField $sortOrderBy";
    
    $filter =' AND r.roleId='.$roleId;
    
    ////////////
    if($roleId !=3 and $roleId!=4){ //for admin/employee
      $totalArray      = $roleManager->getTotalEmployeeRole($filter);
      $roleRecordArray = $roleManager->getEmployeeRoleList($filter,$limit,$orderBy);
      $totalRecords    = count($totalArray);
    }
    else if($roleId==3){//for parents
      $totalArray      = $roleManager->getTotalParentRole($filter);
      $roleRecordArray = $roleManager->getParentRoleList($filter,$limit,$orderBy);
      $totalRecords    = count($totalArray);
    }
    else if($roleId==4){//for students
      $totalArray      = $roleManager->getTotalStudentRole($filter);
      $roleRecordArray = $roleManager->getStudentRoleList($filter,$limit,$orderBy);
      $totalRecords    = count($totalArray);
    }
    else{
        echo 'Invalid Role';
        die;
    }
    
    $cnt = count($roleRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       //employee 
       if($roleRecordArray[$i]['employeeName']==''){
           $roleRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
       }
       //designation
       if($roleRecordArray[$i]['designationName']==''){
           $roleRecordArray[$i]['designationName']=NOT_APPLICABLE_STRING;
       }
       //displayName
       if($roleRecordArray[$i]['displayName']==''){
           $roleRecordArray[$i]['displayName']=NOT_APPLICABLE_STRING;
       }
       //parentName
       if($roleRecordArray[$i]['parentName']==''){
           $roleRecordArray[$i]['parentName']=NOT_APPLICABLE_STRING;
       }
       //isTeaching
       if($roleRecordArray[$i]['isTeaching']==''){
           $roleRecordArray[$i]['isTeaching']=NOT_APPLICABLE_STRING;
       }
       else{
           if($roleRecordArray[$i]['isTeaching']==1){
               $roleRecordArray[$i]['isTeaching']='Yes';
           }
           else{
               $roleRecordArray[$i]['isTeaching']='No';
           }
       }
       //dob
       if($roleRecordArray[$i]['dateOfBirth']=='' OR $roleRecordArray[$i]['dateOfBirth']=='0000-00-00'){
           $roleRecordArray[$i]['dateOfBirth']=NOT_APPLICABLE_STRING;
       }
       else{
           $roleRecordArray[$i]['dateOfBirth']=UtilityManager::formatDate($roleRecordArray[$i]['dateOfBirth']);
       }
       //doj
       if($roleRecordArray[$i]['dateOfJoining']=='' OR $roleRecordArray[$i]['dateOfJoining']=='0000-00-00'){
           $roleRecordArray[$i]['dateOfJoining']=NOT_APPLICABLE_STRING;
       }
       else{
           $roleRecordArray[$i]['dateOfJoining']=UtilityManager::formatDate($roleRecordArray[$i]['dateOfJoining']);
       }
       //doa
       if($roleRecordArray[$i]['dateOfAdmission']=='' OR $roleRecordArray[$i]['dateOfAdmission']=='0000-00-00'){
           $roleRecordArray[$i]['dateOfAdmission']=NOT_APPLICABLE_STRING;
       }
       else{
           $roleRecordArray[$i]['dateOfAdmission']=UtilityManager::formatDate($roleRecordArray[$i]['dateOfAdmission']);
       }
       $valueArray = array_merge(
                                  array(
                                  'srNo' => ($records+$i+1)
                                ),
                                 $roleRecordArray[$i]
                                );
    
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxGetRoleUserList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:48
//Created in $/LeapCC/Library/Role
//Made UI changes in Role Master module
?>