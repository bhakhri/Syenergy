<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();
    
    $roleId=trim($REQUEST_DATA['roleId']);
    if($roleId==''){
      echo 'Role Information Missing';   
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
      $roleRecordArray = $roleManager->getEmployeeRoleList($filter,' ',$orderBy);
    }
    else if($roleId==3){//for parents
      $roleRecordArray = $roleManager->getParentRoleList($filter,' ',$orderBy);
    }
    else if($roleId==4){//for students
      $roleRecordArray = $roleManager->getStudentRoleList($filter,' ',$orderBy);
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
    }

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$roleRecordArray[$i]);
   }

	$csvData = '';
    $csvData .= "#, UserName, Display Name,";
    if($roleId!=3 and $roleId!=4){
        $csvData .= " Name, Teaching, Designation, DOJ, DOB \n";
    }
    if($roleId==3){//for parents
        $csvData .= " Name, Type \n";
    }
    if($roleId==4){//for students
        $csvData .= " Name, DOB, DOA \n";
    }
   if($roleId!=3 and $roleId!=4){ 
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['userName']).', '.parseCSVComments($record['displayName']).', '.parseCSVComments($record['employeeName']).','.$record['isTeaching'].','.$record['designationName'].','.$record['dateOfJoining'].','.$record['dateOfBirth'];
		$csvData .= "\n";
	}
   }
   if($roleId==3){//for parents
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['userName']).', '.parseCSVComments($record['displayName']).', '.parseCSVComments($record['parentName']).','.$record['parent'];
        $csvData .= "\n";
    }
   }
   if($roleId==4){//for students
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['userName']).', '.parseCSVComments($record['displayName']).', '.parseCSVComments($record['studentName']).','.$record['dateOfBirth'].','.$record['dateOfAdmission'];
        $csvData .= "\n";
    }
   }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="userReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: userReportCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:49
//Created in $/LeapCC/Templates/Role
//Made UI changes in Role Master module
?>