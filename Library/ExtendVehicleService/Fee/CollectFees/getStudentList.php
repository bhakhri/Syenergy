<?php
//--------------------------------------------------------
//The file contains data base functions for geating students 
// Author :Nishu Bindal
// Created on : 10.05.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
   
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CollectFeesNew');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;
    //////
    
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

		$genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".add_slashes($genName)."%'";
	}  
  
	return $genName;
	}

    $conditions=" AND b.classId=".$REQUEST_DATA['studentClass'];

	//Student Name
	$studentName = $REQUEST_DATA['studentName'];
	if (!empty($studentName)) {
		//$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $conditions .= " AND (
                                  TRIM(a.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(a.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
		 
	}

   /* if($REQUEST_DATA['studentName']){
	
		$conditions=" AND b.classId=".$REQUEST_DATA['studentClass'];
	}*/
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";         
    if($REQUEST_DATA['deletedStudent']=='1') {

		$totalArray = $studentManager->getTotalQuarantineStudent($conditions);
		$studentRecordArray = $studentManager->getQuarantineStudentList($conditions,$limit,$orderBy);
		$deleteStudent = 1;
	}
	else{

		$totalArray = $studentManager->getTotalStudent($conditions);
		$studentRecordArray = $studentManager->getStudentList($conditions,$limit,$orderBy);
		$deleteStudent = 0;
	}
	$cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
    	$rollNoRegNo = '';
        // add stateId in actionId to populate edit/delete icons in User Interface
	if($studentRecordArray[$i]['rollNo'] != NOT_APPLICABLE_STRING){
       		$rollNoRegNo = "'".$studentRecordArray[$i]['rollNo']."'";
        }
        else{
        	$rollNoRegNo = "'".$studentRecordArray[$i]['regNo']."'";
        }
       $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                       "students" => '<input type="radio" name="students" id="students" value="'.$studentRecordArray[$i]['studentId'].'~'.$studentRecordArray[$i]['classId'].'~'.$studentRecordArray[$i]['firstName'].'~'.$deleteStudent.'" onClick="fillStudent('.$rollNoRegNo.')">')
                                       , $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
     

?>
