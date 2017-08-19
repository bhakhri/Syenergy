<?php
//-------------------------------------------------------
// Purpose: To store the records of Student Re-apper Subject from the database functionality
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    define('MODULE','DisplayStudentReappear');  
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);   
    UtilityManager::headerNoCache();
   
   
    // To Store studentId, Current ClassId, Re-appear ClassId
    $studentId = add_slashes($REQUEST_DATA['studentId']);   
  
    if($studentId=='') {
      echo "Please select atleast one student record";   
      die();
    }
    
    global $sessionHandler;
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
    if ($sortField == 'subjectName') {
      $sortField1 = 'IF(IFNULL(subjectName,"")="",sub.subjectId, subjectName)';
    }
    else if ($sortField == 'subjectCode') {
      $sortField1 = 'IF(IFNULL(subjectCode,"")="",sub.subjectId, subjectCode)';
    }
    else if ($sortField == 'subjectTypeName') {
      $sortField1 = 'IF(IFNULL(subjectTypeName,"")="",sub.subjectId, subjectTypeName)';
    }
    else {
      $sortField1 = 'IF(IFNULL(subjectName,"")="",sub.subjectId, subjectName)';
      $sortField = "subjectName";
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    

    $condition = " AND (studentId,currentClassId,reapperClassId) IN ($studentId) AND instituteId = $instituteId";

    $studentRecordArray = $studentManager->getStudentReappear($condition,$orderBy);
    $cnt = count($studentRecordArray);
        
    for($i=0;$i<$cnt;$i++) {
       $id = $studentRecordArray[$i]['studentId'];
       $curr = $studentRecordArray[$i]['currentClassId'];
       $reap = $studentRecordArray[$i]['reapperClassId'];
       $subjectId = $studentRecordArray[$i]['subjectId'];
       $statusId = $studentRecordArray[$i]['reppearStatus'];  
       $sDetained = $studentRecordArray[$i]['detained'];  
       $reappearId = $studentRecordArray[$i]['reappearId'];  
       
       $checked="";
       if($sDetained=='Y') {
         $checked = "checked=checked";
       }
        
       $studentDetails = "(".$id.",".$curr.",".$reap.",".$subjectId.")";
       
       $studentRecordArray[$i]['reapStatus'] = '<input type="hidden" name="reappearStatusId1[]" value=\''.$studentDetails.'\' />
                                                <select size="1" style="width:160px" class="selectfield" name="reappearStatusId[]">
                                                '.HtmlFunctions::getInstance()->getReappearStatusData($statusId).'</select>';
                                                
       $studentRecordArray[$i]['studentDetained']   =  '<input type="checkbox" name="studentDetained[]" '.$checked.' value=\''.$studentDetails.'\' />';
       
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
   }
    
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
    
// for VSS
// $History: ajaxAdminStudentApproval.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/14/10    Time: 5:15p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/10    Time: 1:52p
//Updated in $/LeapCC/Library/Student
//instituteId check added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:06p
//Created in $/LeapCC/Library/Student
//initial checkin
?>
