<?php 
// This file is used as csv version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/FollowUpManager.inc.php");;
    $followUpManager = FollowUpManager::getInstance();

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

    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $filter = ''; 
    if (!empty($search)) {
        $search=strtoupper(trim($REQUEST_DATA['searchbox']));
       $contactedVia=-1;
       if($search=='EMAIL'){
           $contactedVia=1;
       }
       elseif($search=='LANDLINE'){
           $contactedVia=2;
       }
       elseif($search=='MOBILE'){
           $contactedVia=3;
       }
       elseif($search=='SMS'){
           $contactedVia=4;
       }
       $filter = ' AND ( c.companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.contactedPerson LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.designation LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.contactedVia LIKE "'.$contactedVia.'%")';         
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyCode';
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $followUpManager->getFollowUpList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $recordArray[$i]['contactedOn'] =UtilityManager::formatDate($recordArray[$i]['contactedOn']);
       if($recordArray[$i]['contactedVia']==1){
           $recordArray[$i]['contactedVia']='Email';
       }
       elseif($recordArray[$i]['contactedVia']==2){
           $recordArray[$i]['contactedVia']='Landline';
       }
       elseif($recordArray[$i]['contactedVia']==3){
           $recordArray[$i]['contactedVia']='Mobile';
       }
       else{
           $recordArray[$i]['contactedVia']='SMS';
       }
       
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = '';
    $csvData .= "#, Company, Contacted On, Contacted Via, Contacted Person, Designation \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['companyCode']).','.parseCSVComments($record['contactedOn']).','.parseCSVComments($record['contactedVia']).','.parseCSVComments($record['contactedPerson']).','.parseCSVComments($record['designation']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="followUps.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>