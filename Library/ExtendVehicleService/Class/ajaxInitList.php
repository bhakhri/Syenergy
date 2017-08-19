<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (01.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ClassManager.inc.php");
    $classManager = ClassManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' (className LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $classManager->getTotalClass($filter);
    $classRecordArray = $classManager->getClassList($filter,$limit,$orderBy);
    $cnt = count($classRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add classId in actionId to populate edit/delete icons in User Interface
		 

	if($classRecordArray[$i]['isActive']==1)
		$classRecordArray[$i]['isActive'] = "Active";
	if($classRecordArray[$i]['isActive']==2)
		$classRecordArray[$i]['isActive'] = "Future";
	if($classRecordArray[$i]['isActive']==3)
		$classRecordArray[$i]['isActive'] = "Past";
	if($classRecordArray[$i]['isActive']==4)
		$classRecordArray[$i]['isActive'] = "Unused";

        $valueArray = array_merge(array('action' => $classRecordArray[$i]['classId'] , 'srNo' => ($records+$i+1) ),$classRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Class
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Class
//updated class status to "active","future","Past","unused"
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 5:26p
//Updated in $/Leap/Source/Library/Class
//replaced isActive by image symbol
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:59a
//Created in $/Leap/Source/Library/Class
//intial checkin
?>
