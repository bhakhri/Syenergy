<?php 
//This file is used as csv version for student fine.
//
// Author :Rajeev Aggarwal
// Created on : 29.07.2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    

	//to parse csv values    
	function parseCSVComments($comments) {
		
		$comments = str_replace('"', '""', $comments);
		$comments = str_ireplace('<br/>', "\n", $comments);
		if(eregi(",", $comments) or eregi("\n", $comments)) {
		
			return '"'.$comments.'"'; 
		} 
		else {
			
			return $comments; 
		}
	}

    global $statusCategoryArr;
    function parseName($value){
		
		$name=explode(' ',$value);
	    $genName="";
		$len= count($name);
		if($len > 0){
			
			for($i=0;$i<$len;$i++){
			
			if(trim($name[$i])!=""){
            
				if($genName!=""){
					
					$genName =$genName ." ".$name[$i];
				}
				else{

					$genName =$name[$i];
				} 
			}
		}
    }
    if($genName!=""){

		$genName=" OR CONCAT(TRIM(firstName),' ',TRIM(lastName)) LIKE '".$genName."%'";
	}  
  
	return $genName;
	}
	$parsedName=parseName(trim($REQUEST_DATA['searchbox']));    //parse the name for compatibality
       
                                
                                
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

	   if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){

			$inService=1;  
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){

			$inService=0;  
       }
       else{

          $inService=-1;
       }

	  $approvedKey =  array_search(trim(ucfirst(strtolower ($REQUEST_DATA['searchbox']))),$statusCategoryArr);
	   if($approvedKey){

			$approveSearch = " OR status =".$approvedKey;
	   }
       $filter = ' AND ( rollNo LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR fineCategoryAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR firstName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR amount LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR noDues LIKE "'.$inService.'%" OR paid LIKE "'.$inService.'%" OR lastName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"'.$parsedName.' '.$approveSearch.'  )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineCategoryName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
	global $sessionHandler;
    $userId = $sessionHandler->getSessionVariable('UserId');

	$filter .="  AND fs.userId = $userId ";
    
    $recordArray = $fineStudentManager->getFineStudentList($filter,$limit,$orderBy);
   

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {

		$recordArray[$i]['fineDate'] = UtilityManager::formatDate($recordArray[$i]['fineDate']);
		$recordArray[$i]['status'] = $statusCategoryArr[$recordArray[$i]['status']]; 
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "Sr, Roll No.,Student Name,Fine Category,Amount,Fine Date,No Dues,Is Paid?,Status \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['rollNo']).', '.parseCSVComments($record['fullName']).', '.parseCSVComments($record['fineCategoryAbbr']).', '.parseCSVComments($record['amount']).', '.parseCSVComments($record['fineDate']).', '.parseCSVComments($record['noDues']).', '.parseCSVComments($record['paid']).', '.parseCSVComments($record['status']);

		 //$csvData .= $record['srNo'].', '.parseCSVComments($record['rollNo']).', '.parseCSVComments($record['fullName'].', '.parseCSVComments($record['fineCategoryAbbr'].', '.parseCSVComments($record['amount'].', '.parseCSVComments($record['fineDate'].', '.parseCSVComments($record['noDues'].', '.parseCSVComments($record['paid'].', '.parseCSVComments($record['status']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="fineFineReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: fineStudentCSV.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:57p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//intial checkin
?>