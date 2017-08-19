<?php 
// This file is used as CSV format for General Survery FeedBack 
//
// Author :Gagan Gill
// Created on : 29-11-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();


     require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
     $empManager = EmployeeManager::getInstance();
    /////////////////////////

     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
              return chr(160).$comments;  
         }
    }
    

      global $mdpSelectedArr;
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        foreach($mdpSelectedArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $mdpSelectId = " OR $mdpSelectId LIKE '%$key%' ";
           break;
         }
       }       
       $condition = ' AND (em.mdpName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.sessions Attended LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.hoursAttended LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.venue LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$mdpSelectId.')'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'mdpName';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND e.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
    ////////////
    $mdpRecordArray = $empManager->getMdpList($condition,$orderBy);
    $cnt = count($mdpRecordArray);
//	die(' '.__LINE__);


    
    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n SearchBy:,".parseCSVComments($REQUEST_DATA['searchbox'])."\n";
    $csvData .= "Sr. No., Mdp Name, Mdp, Start Date, End Date, Venue, Hours,Mdp Type \n";
    
    for($i=0;$i<$cnt;$i++) {
        if($mdpRecordArray[$i]['startDate']=='0000-00-00') {
           $mdpRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $mdpRecordArray[$i]['startDate'] = UtilityManager::formatDate($mdpRecordArray[$i]['startDate']);
        }
        if($mdpRecordArray[$i]['endDate']=='0000-00-00') {
           $mdpRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $mdpRecordArray[$i]['endDate'] = UtilityManager::formatDate($mdpRecordArray[$i]['endDate']);
        }

      $mdpTypeArray = array(1=>'ICTP', 2=>'EDP', 3=>'FDP', 4=>'Seminar', 5=>'Workshop', 6=>'PDP');
      $mdpSelectArray = array(0=>'Attended' , 1=>'Conducted');
   
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
	   
	
        if($mdpRecordArray[$i]['hoursAttended']=="") {
           $mdpRecordArray[$i]['hoursAttended'] = 0;
        } 
	      
        $csvData .= ($i+1).','.parseCSVComments($mdpRecordArray[$i]['mdpName']).','.parseCSVComments($mdpRecordArray[$i]['mdp']).','.parseCSVComments($mdpRecordArray[$i]['startDate']).','.parseCSVComments($mdpRecordArray[$i]['endDate']).','.parseCSVComments($mdpRecordArray[$i]['venue']).','.parseCSVComments($mdpRecordArray[$i]['hoursAttended']).','.parseCSVComments($mdpRecordArray[$i]['mdpType']);
        $csvData .= "\n";
    }  
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="EmployeeMdpReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
	?>