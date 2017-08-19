<?php 
//This file is used as printing version for role.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
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
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' WHERE (rl.roleName LIKE "'.add_slashes($search).'%")';        
    }
    
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="rl.$sortField $sortOrderBy"; 


	$totalArray  = $roleManager->getTotalRole($conditions);
    $recordArray = $roleManager->getRoleList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	 $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="Sr No.,Role Name";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($recordArray[$i]['roleName'])."\n";
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="roleReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
// $History: rolePrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/09/09    Time: 11:08
//Updated in $/LeapCC/Templates/Role
//Done bug fixing.
//Bug ids---
//00001398,00001399,00001400,00001401
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Templates/Role
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Role
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:31a
//Created in $/Leap/Source/Templates/Role
//Added functionality for role report print
?>