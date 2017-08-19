<?php
//-------------------------------------------------------
//  This File contains Validation and print function used in Leave Session Form
//
//
// Author :Parveen Sharma
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");   
    $sessionsManager = LeaveSessionsManager::getInstance();
    
    define('MODULE','LeaveSessionMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE sessionName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                         IF(active=1,"Yes","No") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                         DATE_FORMAT(sessionStartDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                         DATE_FORMAT(sessionEndDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"';    
    }
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);       
    //$search .= "<br>As On ".UtilityManager::formatDate($formattedDate);
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sessionName';
    
    $orderBy = " $sortField $sortOrderBy";

	$sessionRecordArray = $sessionsManager->getSessionList($filter,'',$orderBy);
	$recordCount = count($sessionRecordArray);
                            //$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($sessionRecordArray) ) { 
		for($i=0; $i<$recordCount; $i++ ) {
		  $sessionRecordArray[$i]['sessionStartDate'] =strip_slashes($sessionRecordArray[$i]['sessionStartDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING : UtilityManager::formatDate($sessionRecordArray[$i]['sessionStartDate']);
          $sessionRecordArray[$i]['sessionEndDate'] = strip_slashes($sessionRecordArray[$i]['sessionEndDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($sessionRecordArray[$i]['sessionEndDate']);
          $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),
                                            $sessionRecordArray[$i]);   
		}
	}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Leave Session Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead							=    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']					=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['sessionName']				=    array('Session Name',     ' width=15% align="left" ','align="left" ');
    $reportTableHead['sessionStartDate']		=    array('Start Date',       ' width="15%" align="center" ','align="center"');
	$reportTableHead['sessionEndDate']			=    array('End Date',         ' width="15%" align="center" ','align="center"');
	$reportTableHead['active']					=    array('Active',           ' width="15%" align="center" ','align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
