<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of leave details in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (20.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','OffenseMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/OffenseManager.inc.php");
    $offenseManager = OffenseManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE ( offenseName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR offenseAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'offenseName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray             = $offenseManager->getTotalOffenseDetail($filter);
    $offenseRecordArray = $offenseManager->getOffenseDetail($filter,$limit,$orderBy);
    $cnt = count($offenseRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$studentCount = $offenseRecordArray[$i]['studentCount'];
		$offenceId    = $offenseRecordArray[$i]['offenseId'];
		$linkStr      = "<a href='javascript:printStudentCount(\"$offenceId\");'>".$studentCount."</a>";
		$offenseRecordArray[$i]['studentCount'] = $linkStr;

        $valueArray = array_merge(array('action' => $offenseRecordArray[$i]['offenseId'] , 'srNo' => ($records+$i+1) ),$offenseRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitOffenseList.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/02/09    Time: 3:22p
//Updated in $/LeapCC/Library/Offense
//make search on offense abbr.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:41p
//Created in $/LeapCC/Library/Offense
//ajax files for add, edit or delete
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:14p
//Created in $/Leap/Source/Library/Offense
//ajax files to get offense detail, add, edit or delete
//

?>
