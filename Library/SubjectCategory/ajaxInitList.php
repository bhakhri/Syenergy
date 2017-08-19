<?php
//-------------------------------------------------------
// Purpose: To store the records of group in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SubjectCategory');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
    $subjectCategoryManager =  SubjectCategoryManager::getInstance(); 

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (categoryName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          abbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          subjectCount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'")';
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    
     $orderBy = " ORDER BY $sortField $sortOrderBy";         

    ////////////
    $totalArray = $subjectCategoryManager->getSubjectCategoryList($filter);
    $subjectRecordArray = $subjectCategoryManager->getSubjectCategoryList($filter,$limit,$orderBy);
    $cnt = count($subjectRecordArray);

    for($i=0;$i<$cnt;$i++) {

	 $subjectRecordArray[$i]['subjectCount'] = "<a href='#' style='text-decoration:underline;color:blue' onClick='viewSubjectDetailsWindow(".$subjectRecordArray[$i]['subjectCategoryId']."); return false;'>(".$subjectRecordArray[$i]['subjectCount'].")</a>";
        // add groupId in actionId to populate edit/delete icons in User Interface   
       
	 $valueArray = array_merge(array(
					'action' => $subjectRecordArray[$i]['subjectCategoryId'], 
                                        'srNo' => ($records+$i+1)) , $subjectRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Library/SubjectCategory
//formatting & role permission added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/11/09    Time: 3:44p
//Updated in $/LeapCC/Library/SubjectCategory
//abbr new filed added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/10/09    Time: 3:25p
//Updated in $/LeapCC/Library/SubjectCategory
//search condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/07/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectCategory
//initial checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:11p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:05p
//Updated in $/Leap/Source/Library/Group
//modified for add & edit
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:04p
//Updated in $/Leap/Source/Library/Group
//modified for edit 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 11:06a
//Updated in $/Leap/Source/Library/Group
//modified in functions for edit, add
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:04p
//Created in $/Leap/Source/Library/Group
//containing functions for add, edit, delete or search
?>
