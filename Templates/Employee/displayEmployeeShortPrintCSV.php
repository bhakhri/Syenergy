 <?php 
//This file is used as printing version for display employee.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    
    
     //used to parse csv data
    function parseCSVComments($comments) {
     $comments = str_replace('"', '""', $comments);
     $comments = str_ireplace('<br/>', "\n", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
      } 
      else {
         return chr(160).$comments; 
      }
    }
    
    $search = trim($REQUEST_DATA['searchbox']);
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        if(strtolower(trim($REQUEST_DATA['searchbox']))=='no' || strtolower(trim($REQUEST_DATA['searchbox']))=='n') {
            $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='yes' || strtolower(trim($REQUEST_DATA['searchbox']))=='ye') {
            $type=1;
       }
       else {
            $type=-1;
       }
       
        $filter = ' AND (emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         br.branchCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.contactNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.mobileNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.emailAddress LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.isTeaching LIKE "%'.$type.'%" OR d.abbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )' ; 
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = "$sortField $sortOrderBy"; 

	$employeeRecordArray = $employeeManager->getShortEmployeeList($filter,'',$orderBy);
    
	$recordCount = count($employeeRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData .='Search By,'.parseCSVComments($search);
    $csvData .="\n";  
    $csvData .="#,Name,Emp. Code,Teaching,Deptt.,Contact No.,Mobile No.,Email";
    $csvData .="\n";
    
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['employeeName']).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['employeeCode']).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['isTeaching']).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['departmentAbbr']).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['contactNumber']).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['mobileNumber']).",";
		  $csvData .= parseCSVComments($employeeRecordArray[$i]['emailAddress']).",";
		  $csvData .= "\n";
   }
  
   if($i==0) {
     $csvData .= ",,,No Data Found";    
   }
  
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'EmployeeReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>