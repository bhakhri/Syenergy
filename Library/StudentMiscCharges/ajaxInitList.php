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
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
   
    require_once(MODEL_PATH . "/StudentMiscChargesManager.inc.php");
    $studentMiscChargesManager = StudentMiscChargesManager::getInstance();


    $feeHeadId=trim(add_slashes($REQUEST_DATA['feeHead']));
    $feeClassId = trim(add_slashes($REQUEST_DATA['feeClassId']));
    $studentName = trim(add_slashes($REQUEST_DATA['studentName']));
    $rollNo = trim(add_slashes($REQUEST_DATA['rollNo']));
    
    if(trim($REQUEST_DATA['feeHead'])==''){
       echo 'Required Parameters Missing';
       die;
    }
    
    if(trim($REQUEST_DATA['feeClassId'])==''){
       echo 'Required Parameters Missing';
       die;
    }
    
   
    $condition = '';
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%'  OR s.universityRollNo LIKE '$rollNo%') ";   
    }
    
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";   
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy=" $sortField $sortOrderBy"; 
    
    
    
    $feeCondition = '';
    $totalArray = $studentMiscChargesManager->getStudentList($conditions,'',$orderBy,$feeHeadId,$feeCondition,$feeClassId); 
    $studentRecordArray = $studentMiscChargesManager->getStudentList($conditions,$limit,$orderBy,$feeHeadId,$feeCondition,$feeClassId);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $id = $feeClassId."_".$studentRecordArray[$i]['studentId'];
      
        if($studentRecordArray[$i]['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentRecordArray[$i]['studentPhoto']."?s1=".rand(0,1000);
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
        }
        $imgSrc = "<img src='".$imgSrc."' width='40' height='40' id='studentImageId' class='imgLinkRemove' />";
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
      
        $charges ='';
        if($studentRecordArray[$i]['charges']!=-1) {
          $charges = $studentRecordArray[$i]['charges'];
        }
        $showlink = "<input type='hidden' id='miscStudentId".$id."' name='miscStudentId[]'  value='".$id."' readonly='readonly' >
                     <input type='hidden' id='miscUpdated".$id."'   name='miscUpdated[]'    value='".$charges."' readonly='readonly' > 
                     <input type='text'   id='miscChargesId".$id."' name='miscChargesId[]'  value='".$charges."' class='inputbox' style='width:100px;' maxlength='10' >";
        
        $valueArray = array_merge(array('miscChargesValue' =>  $showlink, 
                                        'srNo' => ($records+$i+1) ),
                                        $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>    