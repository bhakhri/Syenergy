<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fine Previous Details

// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassFineSetUp');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Fee/FineSetUpManager.inc.php");   
$fineSetUpManager = FineSetUpManager::getInstance();  
   

	//print_r($_REQUEST);die;
    $classId  =   $REQUEST_DATA['fineClassId'];	
    $fineTypeId  =  $REQUEST_DATA['fineTypeId'];
    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];	
    $chargesFormat = $REQUEST_DATA['chargesFormat'];	
    $charges = $REQUEST_DATA['charges'];

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

 $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    
     $orderBy = " $sortField $sortOrderBy"; 

      if($classId=='') {
	  $classId =0;
	 }
        $condition="";
	
	 if($fineTypeId!='') {
	  $condition .="AND fn.feeFineTypeId = '$fineTypeId'";
	 }

	if($classId!='') {
	  $condition .="AND fn.classId IN ('$classId')";
	 } 

	if($fromDate!='') {
	  $condition .="AND fn.fromDate = '$fromDate'";
	 }

	if($toDate!='') {
	  $condition .="AND fn.toDate = '$toDate''";
	 }

	if($chargesFormat!='') {
	  $condition .=" AND fn.chargesFormat = '$chargesFormat'";
	 }

	if($charges!='') {
	  $condition .=" AND fn.charges = '$charges'";
	 }    

		
    $foundArray = $fineSetUpManager->getFineSetUpList($condition,$orderBy,$limit);
    
  $cnt = count($foundArray);
   
    for($i=0;$i<$cnt;$i++) { 
	  
      $id = $foundArray[$i]['feeFineId'];  
      $foundArray[$i]['fromDate'] = UtilityManager::formatDate($foundArray[$i]['fromDate']);  
      $foundArray[$i]['toDate'] = UtilityManager::formatDate($foundArray[$i]['toDate']);  
      
      $action1 = '<a href="#" title="Delete">
                    <img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteFineDetail('.$id.')">
                  </a>';
      
	  $valueArray = array_merge(array('action1' => $action1, 
                                      'srNo' => ($records+$i+1) ),
                                      $foundArray[$i]);
        
       if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
       }
       else {
          $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>


