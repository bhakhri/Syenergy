<?php  
// Purpose: To store the records of student in array from the database, pagination and search, delete 
//

// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AllowIp');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/RegistrationForm/AllowIpManager.inc.php");
    $allowIpManager =AllowIpManager::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	
    $filter='';
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $searchBoxData=add_slashes(trim($REQUEST_DATA['searchbox']));
      $filter = " WHERE allowIPNo LIKE '%".$searchBoxData."%'";
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'allowIPNo';
    $orderBy = " $sortField $sortOrderBy";         

	
    $totalArray = $allowIpManager->getTotalIp($filter);
    $ipRecordArray = $allowIpManager->getIpList($filter,$limit,$orderBy);
    $cnt = count($ipRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $id = $ipRecordArray[$i]['allowIPId'];	    
        $action1 = '<a href="" name="bubble" onclick="deleteSubject('.$id.');return false;" >
                    <img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Unblock Student" title="Delete IP"></a>';
        $check="";
     	$span1='';
        $span2='';
        $checkall = "$span1<input type='checkbox' class='inputbox3' name='chb[]' id='chk_".$id."'value='".$id."' $check>$span2";
        $valueArray = array_merge(array('action1' => $action1,
        				                'srNo' => ($records+$i+1) ,
		                			    'checkAll' => $checkall ),
					                    $ipRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
