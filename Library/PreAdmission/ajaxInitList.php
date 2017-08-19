<?php
//-------------------------------------------------------
// Purpose: To store the records of Subject in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
        

    require_once(MODEL_PATH . "/PreAdmissionManager.inc.php");
    $preAdmissionManager = PreAdmissionManager::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'admissionStatus';
    $orderBy = " $sortField $sortOrderBy";         

    $filter='';
    $totalArray = $preAdmissionManager->getTotalPreAdmission($filter);
    $preAdmissionRecordArray = $preAdmissionManager->getPreAdmissionList($filter,$orderBy,$limit);
	
    $cnt = count($preAdmissionRecordArray);
    for($i=0;$i<$cnt;$i++) {
        $id= $preAdmissionRecordArray[$i]['studentId'];
        $action1 = '';
        $action1 = '<img type="image" title="Edit" alt="Edit" name="ddetails" src="'.IMG_HTTP_PATH.'/edit.gif" onClick="return editWindow(\''.$id.'\',\'Edit\'); return false;" />&nbsp;';
        $action1 .= '<img type="image" title="Delete" alt="Delete" name="sdetails" src="'.IMG_HTTP_PATH.'/delete.gif" onClick="return deleteStudent(\''.$id.'\'); return false;" />'; 
        
        
        // add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                        'action1' => $action1 ),
                                        $preAdmissionRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>
