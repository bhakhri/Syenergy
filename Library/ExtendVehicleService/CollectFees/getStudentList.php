<?php

//The file contains data base functions for marks
//
// Author :Rajeev Aggarwal
// Created on : 21.11.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFees');
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

		$genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".add_slashes($genName)."%'";
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
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
		 
	}

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

   	if($REQUEST_DATA['deletedStudent']=='1') {
	  $deleteStudent = 1;
	}
	else{
  	  $deleteStudent = 0;
	}
    $totalArray = $studentManager->getAlumniStudentTotal($conditions,$deleteStudent);
    $studentRecordArray = $studentManager->getAlumniStudentList($conditions,$limit,$orderBy,$deleteStudent);
	$cnt = count($studentRecordArray);

    
    for($i=0;$i<$cnt;$i++) {
	
		//======================check rollNo/universityRollNo/regNo not be null=============
		if($studentRecordArray[$i]['rollNo']==NOT_APPLICABLE_STRING){
			if($studentRecordArray[$i]['universityRollNo']==NOT_APPLICABLE_STRING){
				$rollNo=$studentRecordArray[$i]['regNo'];
			}else{
				$rollNo=$studentRecordArray[$i]['universityRollNo'];			
			}
		}
		else{
			$rollNo=$studentRecordArray[$i]['rollNo'];
		
		}
		//==============================================================
        // add stateId in actionId to populate edit/delete icons in User Interface
       $sendValue = "'".$studentRecordArray[$i]['studentId'].'~'.$rollNo.'~'.$studentRecordArray[$i]['className'].'~'.$studentRecordArray[$i]['firstName'].'~'.$studentRecordArray[$i]['fatherName'].'~'.$deleteStudent."'";
       $studentLink = '<input type="radio" name="students" id="students" value="'.$studentRecordArray[$i]['studentId'].'~'.$studentRecordArray[$i]['classId'].'~'.$studentRecordArray[$i]['firstName'].'~'.$deleteStudent.'" onClick="fillStudent('.$sendValue.')">';

       $span1 = "<font color='red'>";
       $span2 = "";
       if($studentRecordArray[$i]['studentStatus']=='1'){
         $span1 = "";
         $span2 = "";
       }
       else if($studentRecordArray[$i]['isDeleted'] =='1') {
         $span1 = "";
         $span2 = "";
       }	       

       $studentRecordArray[$i]['studentName']=$span1.$studentRecordArray[$i]['studentName'].$span2;
       $studentRecordArray[$i]['rollNo']=$span1.$studentRecordArray[$i]['rollNo'].$span2;
       $studentRecordArray[$i]['fatherName']=$span1.$studentRecordArray[$i]['fatherName'].$span2;
       $studentRecordArray[$i]['universityRollNo']=$span1.$studentRecordArray[$i]['universityRollNo'].$span2;

	  	 		
       $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                       "students" => $studentLink)
                                       , $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
     
	
//$History: getStudentList.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Library/Student
//updated with all the fees enhancements
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-11-25   Time: 4:17p
//Updated in $/LeapCC/Library/Student
//Fixed 2126,2127,2128,2129,2130,
//2131,2132,2133,2134,2135
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:53p
//Created in $/LeapCC/Library/Student
//Intial check in
?>
