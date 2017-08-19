<?php
//-------------------------------------------------------
// Purpose: To store the records of Student Offence.php
// functionality
//
// Author : Parveen Sharma
// Created on : 29-05-2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();
    
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    $condition = '';
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId      
    
    /*if(trim($REQUEST_DATA['classId'])!=''){
        $condition .=" AND cls.classId=".$REQUEST_DATA['classId'];
    }    
    
    if(trim($REQUEST_DATA['groupId'])!=''){
        $condition .=" AND gr.groupId=".$REQUEST_DATA['groupId'];  
    }    
    
    if(trim($REQUEST_DATA['subjectId'])!=''){
        $condition .=" AND tt.subjectId=".$REQUEST_DATA['subjectId'];
    }    
    
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $condition .=' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'"';
    } */   
    
    $condition =" AND tt.employeeId=".$employeeId;
    
    $condition .=" AND s.studentId=".$REQUEST_DATA['studentId'];
     
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'offenseName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    if(trim($REQUEST_DATA['studentId'])!=''){
        $totalArray          = $studentManager->getTotalStudentOffence($condition);
        $resourceRecordArray = $studentManager->getStudentOffenceList($condition,$orderBy,$limit);
    }
    $cnt = count($resourceRecordArray);
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface
       $msg = substr($htmlManager->removePHPJS($resourceRecordArray[$i]['remarks']),0,40)."....";
       $message1 = '<a href="" name="bubble" onclick="showMessageDetails('.$resourceRecordArray[$i]['disciplineId'].',\'divMessage\',400,200);return false;" title="'.$title.'" >'.$msg.'</a>';
       
       $resourceRecordArray[$i]['remarks']=$message1;  
       $resourceRecordArray[$i]['offenseDate']=UtilityManager::formatDate($resourceRecordArray[$i]['offenseDate']); 
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),$resourceRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// $History: ajaxStudentOffence.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/11/09    Time: 12:30
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Fixed Query Error
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 14/10/09   Time: 18:16
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made code and logic changes to take care of optional subjects repaled
//problems
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/30/09    Time: 7:15p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//condition & formatting update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 4:15p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin 
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:53p
//Created in $/LeapCC/Library/Student
//Intial checkin
?>