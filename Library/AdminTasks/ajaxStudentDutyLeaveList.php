<?php
//-----------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student daily attendance in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (18.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $teacherManager = AdminTasksManager::getInstance();

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;  

	$filter =" AND c.classId=".$REQUEST_DATA['classId']." AND g.groupId=".$REQUEST_DATA['groupId']." AND sc.subjectId=".$REQUEST_DATA['subjectId']; 
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $filter .=' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'"';
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    if($sortField=='rollNo'){
        $orderBy = " LENGTH(rollNo)+0,rollNo $sortOrderBy";  //to make "Lexographical" sorting on "rollNo" field
    }
    else{
     $orderBy = " $sortField $sortOrderBy";                                 
    }                                                                  
                                                        
    
    $totalArray            = $teacherManager->getTotalStudentDutyLeave($filter);
    $studentRecordArray    = $teacherManager->getStudentDutyLeaveList($filter,$limit,$orderBy);  

	
    $cnt = count($studentRecordArray);  //count of student records

    for($i=0;$i<$cnt;$i++) {
		$actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow(this.name,'.$studentRecordArray[$i]['studentId'].','.$REQUEST_DATA['classId'].','.$REQUEST_DATA['groupId'].','.$REQUEST_DATA['subjectId'].');return false;" name="'.$studentRecordArray[$i]['studentName'].'"></a>
                    ';
        $valueArray = array_merge(array('action1' => $actionStr,'srNo' => ($records+$i+1),),
        $studentRecordArray[$i]);         

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxStudentDutyLeaveList.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Library/AdminTasks
//Created "Duty Leave" Module
?>