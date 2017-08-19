 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 

    require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");
    $leaveSetMappingManager = LeaveSetMappingManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
	
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (TRIM(ls.leaveSetName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR TRIM(lt.leaveTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR TRIM(lsm.leaveValue) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveSetName';
    
    $sortField2=$sortField;    
    if($sortField=='leaveTypeName'){
        $sortField =' LENGTH(leaveTypeName)+0,leaveTypeName';
    }
    $orderBy = " $sortField $sortOrderBy";
    $sortField=$sortField2;

	$leaveSetMappingRecordArray = $leaveSetMappingManager->getLeaveSetMappingList($filter,'',$orderBy);
    
	$recordCount = count($leaveSetMappingRecordArray);

    //$valueArray = array();

    $csvData ='';
	$csvData .= "Search By : $search";
	$csvData .= "\n";
    $csvData .= "Sr. No.,Leave Set,Leave Type,Leave Value";
    $csvData .= "\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= trim($leaveSetMappingRecordArray[$i]['leaveSetName']).",";
		  $csvData .= trim($leaveSetMappingRecordArray[$i]['leaveTypeName']).",";
		  $csvData .= number_format(trim($leaveSetMappingRecordArray[$i]['leaveValue']),2).chr(160).",";
		  $csvData .= "\n";
   }
   if($recordCount==0){
       $csvData .=",". NO_DATA_FOUND;
   }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'LeaveSetMappingReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
