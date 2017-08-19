<?php
//-------------------------------------------------------
// Purpose: To store the records of training in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TaskMaster');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $taskManager = ParentManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (title LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR shortDesc LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'Result';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $taskManager->getTotalTask($filter);
    $taskRecordArray = $taskManager->getTaskList($filter,$orderBy,$limit);
    $cnt = count($taskRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$taskRecordArray[$i]['dueDate'] = UtilityManager::formatDate($taskRecordArray[$i]['dueDate']);
		$taskRecordArray[$i]['Result'] = UtilityManager::formatDate($taskRecordArray[$i]['Result']);
		if ($taskRecordArray[$i]['status']==1) {
			$taskRecordArray[$i]['status'] =  '<img src='.IMG_HTTP_PATH.'/active.gif border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:default">';	
		}
		else {
			$taskRecordArray[$i]['status'] = '<img src='.IMG_HTTP_PATH.'/deactive.gif border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:default">';	
		}

        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $taskRecordArray[$i]['taskId'] , 'srNo' => ($records+$i+1) ),$taskRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitTaskList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:32p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/27/09    Time: 6:53p
//Updated in $/SnS/Library/Parent
//fixed bugs
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/24/09    Time: 4:33p
//Updated in $/SnS/Library/Parent
//modified in task for parent & student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:10p
//Updated in $/SnS/Library/Parent
//modified for task
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:50p
//Created in $/SnS/Library/Parent
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Library/Task
//add new room if hostel room is different
//new task module in student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:23p
//Created in $/SnS/Library/Task
//new ajax files for add,edit, delete
//
?>
