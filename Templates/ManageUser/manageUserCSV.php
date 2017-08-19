<?php 
//This file is used as printing version for users.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/ManageUserManager.inc.php");
    $manageUserManager = ManageUserManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

     $conditions = '';
	     function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    //search filter
     $filter = '';
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = 'HAVING (userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                          roleUserName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          roleName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                          displayName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
     }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    
    $orderBy = " $sortField $sortOrderBy";  
   

	$recordArray = $manageUserManager->getUserList($filter,'',$orderBy);   
    $cnt = count($recordArray);
	$search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,User Name,Role Name,Name,Display Name,Active";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['userStatus']==1){
            $recordArray[$i]['userStatus']='Yes';
        }
        else{
           $recordArray[$i]['userStatus']='No';
        }  
		$csvData .= ($i+1).",";
		$csvData .= parseCSVComments($recordArray[$i]['userName']).",";
		$csvData .= parseCSVComments($recordArray[$i]['roleName']).",";
		$csvData .= parseCSVComments($recordArray[$i]['roleUserName']).",";
		$csvData .= parseCSVComments($recordArray[$i]['displayName']).",";
		$csvData .= parseCSVComments($recordArray[$i]['userStatus'])."\n";
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="userReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>