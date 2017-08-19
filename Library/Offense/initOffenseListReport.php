<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','OffenseReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
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
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$noOfOffenseValue = $REQUEST_DATA['noOfOffense'];
	$instances = $REQUEST_DATA['instances'];
	$offenseCategory = $REQUEST_DATA['offenseCategory'];

	if ($offenseCategory != "") {
		$condition = "AND sd.offenseId = $offenseCategory";
		$offenseTotalRecordArray = $offenseManager->getTotalOffenseReportDetail($noOfOffenseValue,$instances,$condition,$filter,$orderBy);
	    $offenseRecordArray = $offenseManager->getOffenseReportDetail($noOfOffenseValue,$instances,$condition,$filter,$limit,$orderBy);
		$cnt = count($offenseRecordArray);
	}
	else {
		$offenseTotalRecordArray = $offenseManager->getTotalOffenseReportDetail($noOfOffenseValue,$instances,$condition,$filter,$orderBy);
		$offenseRecordArray = $offenseManager->getOffenseReportDetail($noOfOffenseValue,$instances,$filter,$condition,$limit,$orderBy);
		$cnt = count($offenseRecordArray);
	
	}
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
		$studentId = $offenseRecordArray[$i]['studentId'];
		$classId = $offenseRecordArray[$i]['classId'];
		$offenseId = $offenseRecordArray[$i]['offenseId'];

		$viewDetail = '<img src='.IMG_HTTP_PATH.'/zoom.gif border="0" alt="View" title="View" width="15" height="15" style="cursor:hand" onclick="printReport('.$studentId.','.$classId.','.$offenseId.');return false;" title="View Detail">';
        $valueArray = array_merge(array('viewDetail'=>$viewDetail,'action' => $offenseRecordArray[$i]['disciplineId'] , 'srNo' => ($records+$i+1) ),$offenseRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($offenseTotalRecordArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: initOffenseListReport.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Offense
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/02/09    Time: 5:31p
//Created in $/LeapCC/Library/Offense
//copy from sc to show offense report
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/16/09    Time: 11:24a
//Updated in $/Leap/Source/Library/Offense
//modification in code to show list of student of all classes with
//offenses
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/15/09    Time: 4:47p
//Created in $/Leap/Source/Library/Offense
//new ajax file to show offense report against students
//
?>