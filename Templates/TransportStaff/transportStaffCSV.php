<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
    $tranportManager = TransportStaffManager::getInstance();

    $conditionsArray = array();
    $qryString = "";
    

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

    /// Search filter /////
    if(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='DRIVER' ){
         $trType=1;  
       }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='CONDUCTOR'){
         $trType=2;  
    }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='OTHER'){
         $trType=3;  
    }
    else{
        $trType=-1;
    }
    
    if(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='YES' ){
         $verificationDone = 1;  
       }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='NO'){
     $verificationDone = 0;
    }
    else{
      $verificationDone = -1;
    }
        
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $conditions = ' WHERE  ( name LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR staffCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR staffType LIKE "'.$trType.'%" OR DATE_FORMAT(dlExpiryDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  DATE_FORMAT(joiningDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR dlNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR verificationDone LIKE "'.$verificationDone.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';
    
    $orderBy = " $sortField $sortOrderBy"; 


    $recordArray = $tranportManager->getTransportStaffList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['dlExpiryDate'] = UtilityManager::formatDate($recordArray[$i]['dlExpiryDate']);
        $recordArray[$i]['joiningDate']  = UtilityManager::formatDate($recordArray[$i]['joiningDate']);
        $recordArray[$i]['staffType']    = $transportStaffTypeArr[$recordArray[$i]['staffType']];
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "Sr, Staff, Code, Joining Date, Type, Verification Done, License, Expiry Date \n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['name']).','.parseCSVComments($record['staffCode']).', '.parseCSVComments($record['joiningDate']).','.parseCSVComments($record['staffType']).','.parseCSVComments($record['verificationDone']).','.parseCSVComments($record['dlNo']).', '.parseCSVComments($record['dlExpiryDate']);
        $csvData .= "\n";
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="transportStaff.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: transportStaffCSV.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:33p
//Updated in $/Leap/Source/Templates/TransportStaff
//fixed bug nos. 0002370,0002369,0002365,0002363,0002362,0002361,0002368,
//0002366,0002360,0002359,0002372,0002358,0002357
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Templates/TransportStaff
//put DL image in transport staff and changes in modules
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Templates/TransportStaff
//add new fields and upload image
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/08/09    Time: 10:39
//Created in $/Leap/Source/Templates/TransportStuff
//done bug fixing.
//bug ids---
//0000844,0000845,0000847,0000850,000843
?>