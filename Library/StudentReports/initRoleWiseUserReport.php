<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Jaineesh
// Created on : 19-Jan-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoleWiseList');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    /////////////////////////
    
    $roleId = $REQUEST_DATA['roleId'];
	
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $studentReportsManager->getTotalUserData($roleId);
    $roleWiseUserRecordArray = $studentReportsManager->getUserData($limit,$orderBy,$roleId);

    $cnt = count($roleWiseUserRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add designationId in actionId to populate edit/delete icons in User Interface   

		$userId = $roleWiseUserRecordArray[$i]['userId'];
		$loginDetail = '<img src='.IMG_HTTP_PATH.'/zoom.gif border="0" alt="View" title="View" width="15" height="15" style="cursor:hand" onclick="showUserRoleDetail('.$userId.',\'divUserRole\',900,400);return false;" title="Login Detail">';
        $valueArray = array_merge(array('loginDetail' => $loginDetail, 'srNo' => ($records+$i+1) ),$roleWiseUserRecordArray[$i]);

		if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
		}
		else {
            $json_val .= ','.json_encode($valueArray);           
		}
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 


// $History: initRoleWiseUserReport.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Library/StudentReports
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/28/09    Time: 6:12p
//Updated in $/LeapCC/Library/StudentReports
//modification in files to run role wise graphs & report in leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/28/09    Time: 4:39p
//Created in $/LeapCC/Library/StudentReports
//copy from sc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Library/ScStudentReports
//modified in feedback label & role wise graph
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/10/09    Time: 6:49p
//Updated in $/Leap/Source/Library/ScStudentReports
//modified the files to show graphs quartely wise
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/22/09    Time: 10:07a
//Created in $/Leap/Source/Library/ScStudentReports
//new file to show role wise user report
//

?>
