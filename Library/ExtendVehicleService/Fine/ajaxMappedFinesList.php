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
    define('MODULE','AssignFinetoRoles');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter =' HAVING fineCategoryAbbrs LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       			  userNames LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       			  roleNames LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
       			  instituteId LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"
       			';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleName';
    
    if($REQUEST_DATA['sortField']=='userNames'){
        $sortF='userName';
    }
    elseif($REQUEST_DATA['sortField']=='roleNames'){
        $sortF='roleName';
    }
    elseif($REQUEST_DATA['sortField']=='fineCategoryAbbrs'){
        $sortF='fineCategoryAbbr';
    }
	 elseif($REQUEST_DATA['sortField']=='instituteId'){
        $sortF='instituteAbbr';
    }
    else{
        $sortF='roleName';
    }
    $orderBy = " $sortF $sortOrderBy";         

    
    $totalArray      = $fineManager->getTotalMappedFine($filter);
    $fineRecordArray = $fineManager->getMappedFineList($filter,$limit,$orderBy);
    $roleUserName=$fineManager->getRoleAssociatedUserNames();

    $cnt = count($fineRecordArray);
    $cnt2=count($roleUserName);
    for($i=0;$i<$cnt;$i++){
        $fineRecordArray[$i]['associativeEmployee']='';
	    for($j=0;$j<$cnt2;$j++){
		  if($fineRecordArray[$i]['roleNames']==$roleUserName[$j]['roleName']) {	
            if($fineRecordArray[$i]['associativeEmployee']!='') {
              $fineRecordArray[$i]['associativeEmployee'] .=', ';  
            }    
		    $fineRecordArray[$i]['associativeEmployee'].=$roleUserName[$j]['associativeEmployee'];
		  }
	    }
	    if($fineRecordArray[$i]['associativeEmployee']=='') {
 	      $fineRecordArray[$i]['associativeEmployee']=NOT_APPLICABLE_STRING;
	    }
	}

    $cnt4=count($roleInstituteName);
	for($i=0;$i<$cnt;$i++) {
       $fineRecordArray[$i]['instituteId'] = str_replace(",",", ",$fineRecordArray[$i]['instituteId']); 
       $fineRecordArray[$i]['fineCategoryAbbrs'] = str_replace(",",", ",$fineRecordArray[$i]['fineCategoryAbbrs']); 
       if(strlen($fineRecordArray[$i]['fineCategoryAbbrs'])>=100){
           $fineRecordArray[$i]['fineCategoryAbbrs']=substr($fineRecordArray[$i]['fineCategoryAbbrs'],0,97).'...';
       }
       if(strlen($fineRecordArray[$i]['userNames'])>=40){
           $fineRecordArray[$i]['userNames']=substr($fineRecordArray[$i]['userNames'],0,37).'...';
       }		            

       $valueArray = array_merge(array('action' => $fineRecordArray[$i]['roleFineId'] , 'srNo' => ($records+$i+1) ),$fineRecordArray[$i] );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	    
?>
