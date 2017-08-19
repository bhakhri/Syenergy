<?php
//-------------------------------------------------------
// Purpose: To store the records of test type in array from the database, pagination and search, delete 
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
    define('MODULE','TestTypeCategoryMaster');
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

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
       }
	   else {
		   $type=-1;
	   }

       $filter = ' AND (ttc.testTypeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttc.testTypeAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttc.examType LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR CONCAT(u.universityCode,"-",st.subjectTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttc.showCategory LIKE  "%'.$type.'%" OR ttc.isAttendanceCategory LIKE  "%'.$type.'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testTypeName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $testTypeManager->getTotalTestTypeCategory($filter);
    $testtypeRecordArray = $testTypeManager->getTestTypeCategoryList($filter,$limit,$orderBy);
    $cnt = count($testtypeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
		$testtypeRecordArray[$i]['colorCode']='<div style="height:20px;width:35px;background-color:#'.$testtypeRecordArray[$i]['colorCode'].'"></div>';
        $valueArray = array_merge(array('action' => $testtypeRecordArray[$i]['testTypeCategoryId'] , 'srNo' => ($records+$i+1) ),$testtypeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitTestTypeCategoryList.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 3/31/10    Time: 4:45p
//Updated in $/LeapCC/Library/TestType
//added university wise subject types. FCNS No.1506
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Library/TestType
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/24/09    Time: 2:46p
//Updated in $/LeapCC/Library/TestType
//fixed issue no.0001212
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Library/TestType
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Library/TestType
//modified for test type category
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/26/09    Time: 4:39p
//Updated in $/LeapCC/Library/TestType
//add new fields in test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/17/09    Time: 4:52p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/09/09    Time: 1:05p
//Updated in $/Leap/Source/Library/TestType
//add test type category master
//modified test type
//Bulk Attendance, 
//Daily Attendance 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:32a
//Created in $/Leap/Source/Library/TestType
//new ajax files for test type category
//

  
?>
