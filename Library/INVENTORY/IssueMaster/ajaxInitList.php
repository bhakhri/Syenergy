<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','IssueMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
    $issueManager = IssueManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND (iri.indentNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR emp1.employeeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'indentNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray         = $issueManager->getTotalIssuedIndent($filter);
    $issueRecordArray   = $issueManager->getIssuedIndentList($filter,$limit,$orderBy);
    $cnt = count($issueRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $issueRecordArray[$i]['dated']     = UtilityManager::formatDate($issueRecordArray[$i]['dated']);
        $issueRecordArray[$i]['issueDate'] = UtilityManager::formatDate($issueRecordArray[$i]['issueDate']);
        
        $actionStr='<a href="#" title="View"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" onClick="editWindow('.$issueRecordArray[$i]['indentId'].');"/></a>';


        $valueArray = array_merge(array('actionStr' => $actionStr , 'srNo' => ($records+$i+1) ),$issueRecordArray[$i]);

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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Library/INVENTORY/IssueMaster
//Fixed bugs found during self-testing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/09/09   Time: 18:53
//Created in $/Leap/Source/Library/INVENTORY/IssueMaster
//Created "Issue Master" under inventory management in leap
?>