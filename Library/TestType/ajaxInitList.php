<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TestTypesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $testTypeManager = TestTypeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (tt.testTypeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR tt.testTypeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR tt.testTypeAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR tt.weightageAmount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR eve.evaluationCriteriaName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.universityName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR deg.degreeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testTypeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $testTypeManager->getTotalTestType($filter);
    $testtypeRecordArray = $testTypeManager->getTestTypeList($filter,$limit,$orderBy);
    $cnt = count($testtypeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $testtypeRecordArray[$i]['testTypeId'] , 'srNo' => ($records+$i+1) ),$testtypeRecordArray[$i]);

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
//*****************  Version 4  *****************
//User: Administrator Date: 12/06/09   Time: 11:08
//Updated in $/LeapCC/Library/TestType
//Done bug fixing.
//bug ids----0000032,0000036,0000043
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/05/09   Time: 19:09
//Updated in $/LeapCC/Library/TestType
//Corrected query params
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Library/TestType
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Library/TestType
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/08    Time: 1:21p
//Updated in $/Leap/Source/Library/TestType
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Library/TestType
//Modified DataBase Column names
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Library/TestType
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/30/08    Time: 10:39a
//Created in $/Leap/Source/Library/TestType
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Library/City
//Added AjaxList Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/27/08    Time: 5:08p
//Created in $/Leap/Source/Library/City
//Initial Checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
  
?>
