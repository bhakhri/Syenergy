<?php
//-------------------------------------------------------
// Purpose: To display the records of display Events of Institute
//
// Author : Jaineesh
// Created on : 10-09-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	
		global $FE;
		require_once($FE . "/Library/common.inc.php");
		require_once(BL_PATH . "/UtilityManager.inc.php");
		UtilityManager::ifStudentNotLoggedIn();
		UtilityManager::headerNoCache();

		require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
		$commonQueryManager = CommonQueryManager::getInstance(); 
		
		/////////////////////////
		

		// to limit records per page    
		$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
		$records    = ($page-1)* RECORDS_PER_PAGE;
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
		
		//////
		/// Search filter /////  
	 //   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   //    $filter = ' AND (n.noticeSubject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
		//}
		$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
		$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
		
		$orderBy = " $sortField $sortOrderBy";         

    	$studentId= $sessionHandler->getSessionVariable('StudentId');

		    	
		$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
		
		$classId = $classIdArray[0]['classId'];
	
		$totalRecordArray = CommonQueryManager::getInstance()->countScAttendance1($studentId,$classId);
		$recordCount = count($totalRecordArray);
		$totalRecords = $recordCount;
		
		$studentInformationArray = $commonQueryManager->getScAttendance1($studentId,$classId,$limit,"", "order by $orderBy");

				
		$cnt = count($studentInformationArray);
	
	for($i=0;$i<$cnt;$i++) {
		$studentInformationArray[$i]['fromDate']=strip_slashes($studentInformationArray[$i]['fromDate']);
		$studentInformationArray[$i]['toDate']=strip_slashes($studentInformationArray[$i]['toDate']);
		$studentInformationArray[$i]['Percentage']=number_format((($studentInformationArray[$i]['attended'] /  $studentInformationArray[$i]['delivered'])*100),2,'.','');
		// add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentInformationArray[$i]);
        
         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    //print_r($valueArray);
//   echo '{"totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>