<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (20.01.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
function trim_output($val){
 return (trim($val)!="" ? $val : "Not Present");   
}


    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();
    
//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Jaineesh
//Date:20.01.2009
//-----------------------------------------------------------------------------------
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
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_ADMIN_MESSAGE;

    //////
    
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $groupId=trim($REQUEST_DATA['groupId']);
    
    if($timeTableLabelId=='' or $classId=='' or $groupId==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    $conditions=' AND c.classId='.$classId.' AND sg.groupId='.$groupId.' AND ttc.timeTableLabelId='.$timeTableLabelId;
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
	
    $totalArray = $sendMessageManager->getTotalParentListForStudentPerformance($conditions);
	
	$studentRecordArray = $sendMessageManager->getParentListForStudentPerformance($conditions,$limit,$orderBy);

	$cnt = count($studentRecordArray);
	for($i=0;$i<$cnt;$i++) {
        $sdisable=(trim($studentRecordArray[$i]['studentName'])!="" ? " "   : "disabled");  
		$fdisable=(trim($studentRecordArray[$i]['fatherUserId'])!="" ? " "   : "disabled"); 
		$mdisable=(trim($studentRecordArray[$i]['motherUserId'])!="" ? " "   : "disabled");
		$gdisable=(trim($studentRecordArray[$i]['guardianUserId'])!="" ? " " : "disabled");
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
       "studentName" => "<input type=\"checkbox\" name=\"students\" id=\"students\" $sdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['studentName'])),
       "fatherName" => "<input type=\"checkbox\" name=\"fathers\" id=\"fathers\" $fdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['fatherName'])),
       "motherName" => "<input type=\"checkbox\" name=\"mothers\" id=\"mothers\" $mdisable  value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['motherName'])),
       "guardianName" => "<input type=\"checkbox\" name=\"guardians\" id=\"guardians\" $gdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['guardianName'])),
       'rollNo' => strip_slashes($studentRecordArray[$i]['rollNo']),
       'universityRollNo' =>strip_slashes($studentRecordArray[$i]['universityRollNo'])));

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.'],"studentInfo" : '.json_encode($totalArray).'}'; 
    
// for VSS
// $History: ajaxStudentPerformanceMessageList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/03/10   Time: 13:50
//Created in $/LeapCC/Library/AdminMessage
//Modified search filter in "Send student performance message to parents"
//module
?>