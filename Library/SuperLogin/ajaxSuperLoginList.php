<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','SuperLogin');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
    require_once(MODEL_PATH . "/SuperLoginManager.inc.php");
    $superLogin = SuperLoginManager::getInstance();

	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";      
   
   
    $filter = "";
    $batchId = htmlentities(add_slashes(trim($REQUEST_DATA['batchId']))); 
    $branchId = htmlentities(add_slashes(trim($REQUEST_DATA['branchId']))); 
    $classId = htmlentities(add_slashes(trim($REQUEST_DATA['classId'])));
    $degreeId = htmlentities(add_slashes(trim($REQUEST_DATA['degreeId'])));
    $rollNo = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo'])));
    $studentName = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 
    
    
    if($batchId!='') {
      $filter .= " AND b.batchId = '$batchId' ";
    }
    
    if($branchId!='') {
      $filter .= " AND b.branchId = '$branchId' ";
    }
    
    if($classId!='') {
      $filter .= " AND b.classId = '$classId' ";
    }
    
    if($degreeId!='') {
      $filter .= " AND b.degreeId = '$degreeId' ";
    }
    
    if($rollNo!='') {
      $filter .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    
    if($studentName!='') {
      $filter .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%' )";
    }
    
    if($rollNo!='') {
      $filter .= " AND (s.fatherName LIKE '$fatherName%')";
    }
    
    
    $totalArray = $superLogin->getSuperLoginStudentTotal($filter);
    $studentRecordArray = $superLogin->getSuperLoginStudentList($filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if($studentRecordArray[$i]['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentRecordArray[$i]['studentPhoto'];
               $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
               $checkall = NOT_APPLICABLE_STRING;
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
          $checkall = NOT_APPLICABLE_STRING;
        }
        
        $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove' />";
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        
		$studentRecordArray[$i]['rollNo'] = $studentRecordArray[$i]['rollNo'] == '' ? '--' : $studentRecordArray[$i]['rollNo'] ;

		$studentRecordArray[$i]['universityRollNo'] = $studentRecordArray[$i]['universityRollNo'] == '' ? '--' : $studentRecordArray[$i]['universityRollNo'] ;
		
		//$showlink = "<a href='studentDetail.php?id=".$studentRecordArray[$i]['studentId'].$qryString."&classId=".$studentRecordArray[$i]['class_id']."&page=".$page."&sortField=".$sortField."&sortOrderBy=".$sortOrderBy."' alt='Detail' title='Detail'><img src='".IMG_HTTP_PATH."/zoom.gif' border='0' /></a>&nbsp;&nbsp;<a href='#' onClick='printStudentReport(".$studentRecordArray[$i]['studentId'].",".$studentRecordArray[$i]['class_id'].")' title='Print'><img src='".IMG_HTTP_PATH."/print1.gif' border='0' /></a>";

		if(trim($studentRecordArray[$i]['studentName'])==''){
           $studentRecordArray[$i]['studentName']=NOT_APPLICABLE_STRING;
        }
        if(trim($studentRecordArray[$i]['fatherName'])==''){
           $studentRecordArray[$i]['fatherName']=NOT_APPLICABLE_STRING;
        }
        if(trim($studentRecordArray[$i]['motherName'])==''){
           $studentRecordArray[$i]['motherName']=NOT_APPLICABLE_STRING;
        }
        if(trim($studentRecordArray[$i]['guardianName'])==''){
           $studentRecordArray[$i]['guardianName']=NOT_APPLICABLE_STRING;
        }
        
        if(trim($studentRecordArray[$i]['userId'])!=''){
            $studentRecordArray[$i]['studentName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['userId']).');" title="Click to go to student login" >'.trim($studentRecordArray[$i]['studentName']).'</a>';
        }
        if(trim($studentRecordArray[$i]['fatherUserId'])!=''){
            $studentRecordArray[$i]['fatherName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['fatherUserId']).');" title="Click to go to parent login" >'.trim($studentRecordArray[$i]['fatherName']).'</a>';
        }
        if(trim($studentRecordArray[$i]['motherUserId'])!=''){
            $studentRecordArray[$i]['motherName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['motherUserId']).');" title="Click to go to parent login" >'.trim($studentRecordArray[$i]['motherName']).'</a>';
        }
        if(trim($studentRecordArray[$i]['guardianUserId'])!=''){
            $studentRecordArray[$i]['guardianName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['guardianUserId']).');" title="Click to go to parent login" >'.trim($studentRecordArray[$i]['guardianName']).'</a>';
        }

        $valueArray = array_merge(array('act' =>  $showlink, 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>
