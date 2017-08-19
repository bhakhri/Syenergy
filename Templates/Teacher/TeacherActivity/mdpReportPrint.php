<?php 
//This file is used as CSV format for General Survery FeedBack 
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
     $mdpManager = EmployeeManager::getInstance();
    /////////////////////////
    
       
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    global $mdpSelectedArr;
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        foreach($mdpSelectedArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $mdpSelectId = " OR mdpSelectId LIKE '%$key%' ";
           break;
         }
       }       
        $condition = ' AND (em.mdpName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.sessionsAttended LIKE  "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.hoursAttended LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.venue LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$mdpSelectId.')'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'mdpName';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND em.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
    ////////////
    $mdpRecordArray = $mdpManager->getMdpList($condition,$orderBy);
    $cnt = count($mdpRecordArray);
    
         
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];
    
    
    for($j=0;$j<$cnt;$j++){
        
        if($mdpRecordArray[$j]['startDate']=='0000-00-00') {
           $mdpRecordArray[$j]['startDate'] = NOT_APPLICABLE_STRING;
        }
		 else {
           $mdpRecordArray[$j]['startDate'] = UtilityManager::formatDate($mdpRecordArray[$j]['startDate']);
        }
      
        
        if($mdpRecordArray[$j]['endDate']=='0000-00-00') {
           $mdpRecordArray[$j]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $mdpRecordArray[$j]['endDate'] = UtilityManager::formatDate($mdpRecordArray[$j]['endDate']);
        }
	}
    $mdpTypeArray = array(1=>'ICTP', 2=>'EDP', 3=>'FDP', 4=>'Seminar', 5=>'Workshop', 6=>'PDP');
	$mdpSelectArray = array(0=>'Conducted' , 1=>'Attended');
   
    for($i=0;$i<$cnt;$i++) {
	   $mdpType = $mdpRecordArray[$i]['mdpType'];
	   
	   $mdpArray = explode(',', $mdpType);
	   $str = '';
	   foreach ($mdpArray as $rec) {
		   if (!empty($str)) {
			   $str .= ',';
		   }
		   $str .= $mdpTypeArray[$rec];
	   }
	   $mdpRecordArray[$i]['mdpType'] = $str;
	   
	   if($mdpRecordArray[$i]['mdpType'] == '') {
           $mdpRecordArray[$i]['mdpType'] = NOT_APPLICABLE_STRING;
       }
       
	   if($mdpRecordArray[$i]['mdp'] == '') {
           $mdpRecordArray[$i]['mdp'] = NOT_APPLICABLE_STRING;
       }
	   
       $mdpSelect = $mdpRecordArray[$i]['mdp'];
	   $mdpArray2 = explode(',',$mdpSelect);
       $str2 = '';
	   foreach ($mdpArray2 as $rec2) {
		   if (!empty($str2)) {
			   $str2 .= ',';
		   }
		   $str2 .= $mdpSelectArray[$rec2];
	   }
	   $mdpRecordArray[$i]['mdp'] = $str2;
          if($mdpRecordArray[$i]['venue'] == '') {
           $mdpRecordArray[$i]['venue'] = NOT_APPLICABLE_STRING;
       }
     
        
        if($mdpRecordArray[$i]['sessionsAttended']==0 || $mdpRecordArray[$i]['sessionsAttended']=="") {
           $mdpRecordArray[$i]['sessionsAttended'] = 0;
       } 
        
       if($mdpRecordArray[$i]['hoursAttended']==0 || $mdpRecordArray[$i]['hoursAttended']=="") {
           $mdpRecordArray[$i]['hoursAttended'] = 0;
       }

        $valueArray[$i] = array_merge(array('srNo' => ($records+$i+1) ),$mdpRecordArray[$i]);
        
        $reportHead  = "Employee Name&nbsp;:&nbsp;".$mdpRecordArray[$i]['employeeName'];
        $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$mdpRecordArray[$i]['employeeCode'];
	   }
    
    $reportHead .= "<br>SearchBy&nbsp;:&nbsp;".$REQUEST_DATA['searchbox'];
 
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Mdp  Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']                   =    array('#','width="2%"  align="center"', 'align="center" valign="top"');
    $reportTableHead['mdpName']                =    array('Mdp Name','width=12%   align="left"','align="left"  valign="top"');
    $reportTableHead['startDate']              =    array('Start Date','width="8%" align="center"','align="center" valign="top"');
    $reportTableHead['endDate']                =    array('End Date','width="8%" align="center"','align="center" valign="top"');
    $reportTableHead['mdpType']                =    array('Mdp Type','width="15%" align="center"','align="left" valign="top"');
    $reportTableHead['sessionsAttended']       =    array('Session','width="15%" align="right"','align="right" valign="top"');
    $reportTableHead['hoursAttended']          =    array('Hours','width="15%" align="right"','align="right" valign="top"');
    $reportTableHead['mdp']                    =    array('MDP','width="15%" align="center"','align="left" valign="top"');
    $reportTableHead['venue']                  =    array('Venue','width="15%" align="center"','align="left" valign="top"');  
       
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 ?>