<?php
//-------------------------------------------------------
//  This File contains logic for groups
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Groups');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . '/Accounts/GroupsManager.inc.php');
    $groupsManager = GroupsManager::getInstance();


    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   $filter = " AND (grp.groupName LIKE '".$REQUEST_DATA['searchbox']."%' OR pgrp.groupName LIKE '".$REQUEST_DATA['searchbox']."%')";
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'grp.groupName';
    
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $groupsManager->getTotalGroups($filter);
    $groupsRecordArray = $groupsManager->getGroupsList($filter,$limit,$orderBy);
	
    $cnt = count($groupsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {

		$valueArray = array_merge(array('action' => $groupsRecordArray[$i]['groupId'] , 'srNo' => ($records+$i+1) ),$groupsRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/Groups
//file added
//




?>