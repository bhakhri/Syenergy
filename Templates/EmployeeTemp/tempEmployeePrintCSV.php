 <?php 
//This file is used as printing version for display Hostel.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/EmployeeTempManager.inc.php");
    $tempEmployeeManager = TempEmployeeManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if (!empty($search)) {
        if(strtolower(trim($search))=='on job'){
           $sat=1;
       }
       elseif(strtolower(trim($search))=='left job'){
           $sat=2;
       }
       else{
           $sat=-1;
       } 
        $conditions =' AND (et.tempEmployeeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR et.address LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"
        OR et.contactNo LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR et.status LIKE "%'.$sat.'%" OR dt.designationName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';        
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tempEmployeeName';
    
    $orderBy = " $sortField $sortOrderBy";

	$recordArray = $tempEmployeeManager->getTempEmployeeList($conditions,'',$orderBy);
    
	$recordCount = count($recordArray);

    $valueArray = array();

    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Employee Name,Address,Contact No.,Date Of Joining,Status,Designation";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $recordArray[$i]['status']=$statusArr[$recordArray[$i]['status']];
		  $recordArray[$i]['dateOfJoining']=UtilityManager::formatDate($recordArray[$i]['dateOfJoining']);
		  $csvData .= ($i+1).",";
		  $csvData .= $recordArray[$i]['tempEmployeeName'].",";
		  $csvData .= $recordArray[$i]['address'].",";
		  $csvData .= $recordArray[$i]['contactNo'].",";
		  $csvData .= $recordArray[$i]['dateOfJoining'].",";
		  $csvData .= $recordArray[$i]['status'].",";
		  $csvData .= $recordArray[$i]['designationName'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'TempEmployeeReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>