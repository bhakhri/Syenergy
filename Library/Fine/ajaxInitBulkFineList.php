<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','COMMON');
	define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
    
	require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();
    
	global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    $userId = $sessionHandler->getSessionVariable('UserId');

    if($roleId==1){
     UtilityManager::ifNotLoggedIn(true);
    }
    else if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else if($roleId==5){
      UtilityManager::ifManagementNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();
    
    $condition = "";

	$searchAllClassId = trim($REQUEST_DATA['searchAllClassId']);
    $fineInstiuteId = trim($REQUEST_DATA['fineInstiuteId']);
	$fineClassId = trim($REQUEST_DATA['fineClassId']);

	if($fineClassId=='') {
	  $fineClassId = $searchAllClassId;	
	}

	if($fineClassId=='') {
      $fineClassId=0;
	}

	$condition = " AND c.classId IN ($fineClassId) "; 
	if($fineInstiuteId!='') {
      $fineInstiuteId = " AND c.instituteId = '$fineInstiuteId' ";
	}


	// to limit records per page    
    $page     = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records  = ($page-1)* 500;
    $limit    = ' LIMIT '.$records.',500';

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";         

	$totalArray = $fineStudentManager->getTotalStudent($condition);
	$studentRecordArray = $fineStudentManager->getStudentList($condition,$limit,$orderBy);
	$cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $studentId = $studentRecordArray[$i]['studentId'];
        $classId = $studentRecordArray[$i]['classId'];
       
        $studentIds = $studentId.'~'.$classId;
        $checkall = '<input type="checkbox" name="chb[]"  value="'.$studentIds.'">';
                     
        if($studentRecordArray[$i]['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentRecordArray[$i]['studentPhoto'];
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
        }
        
        $imgSrc = "<img src='".$imgSrc."' width='35' height='35' id='studentImageId' class='imgLinkRemove' />";
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        
		$studentRecordArray[$i]['rollNo'] = $studentRecordArray[$i]['rollNo'] == '' ? '--' : $studentRecordArray[$i]['rollNo'] ;
		$studentRecordArray[$i]['universityRollNo'] = $studentRecordArray[$i]['universityRollNo'] == '' ? '--' : $studentRecordArray[$i]['universityRollNo'] ;
		
        $valueArray = array_merge(array('checkAll' =>  $checkall, 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
          $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>
