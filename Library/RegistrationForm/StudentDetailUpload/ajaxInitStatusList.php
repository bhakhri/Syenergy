<?php
//-------------------------------------------------------
// Purpose: To design the Student Fee Concession Mapping     
//
// Author : Ankur Aggarwal
// Created on : (25.08.2011 )
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    define('MODULE','ScholarStatusDetails');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	
    require_once(MODEL_PATH . "/RegistrationForm/ScStudentStatusUpload.inc.php");
    $studentStatusManager=StudentStatusUpload::getInstance();

    
    $classId = add_slashes($REQUEST_DATA['classId']);   
    $rollNo = add_slashes($REQUEST_DATA['rollNo']);
    $studentName = add_slashes($REQUEST_DATA['studentName']);
   
    $condition = '';

    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%'  OR s.universityRollNo LIKE '$rollNo%') ";   
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";   
    }

   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = "$sortField $sortOrderBy";
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    
    $checkCondition = " classId IN ($classId)"; 
 //   $checkConcessionArray = $studentFeeConcessionManager->getCheckStudentConcession($checkCondition); 
    
     
   
    $studentRecordArray= $studentStatusManager->getStudentList($condition,'',$orderBy,$classId);


   // print_r($studentRecordArray);die;

    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $id = $studentRecordArray[$i]['studentId'];
        $studentName = $studentRecordArray[$i]['studentName'];
        $univRollNo=$studentRecordArray[$i]['universityRollNo'];
        
	
        if($studentRecordArray[$i]['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentRecordArray[$i]['studentPhoto']."?ss=".rand(0,1000); 
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

        $span1='';
        $span2='';
        $checked='';
        if($studentRecordArray[$i]['dayScholar']==1) {
            $span1='<span style="background-color:RED">';
            $span2='</span>';
            $checked='checked=checked';
	    }
	    $dayScholar = "$span1<input type='checkbox' id='dayScholar_$id' name='chb1[]' onChange='getStatus($id,\"D\"); return false;' value='$id' $checked >$span2";

        $span1='';
        $span2='';
        $checked='';
        if($studentRecordArray[$i]['hostler']==1) {
           $span1='<span style="background-color:RED">';
           $span2='</span>';
           $checked='checked=checked';
        }
        $hostler = "$span1<input type='checkbox' id='hostler_$id' name='chb2[]' onChange='getStatus($id,\"H\"); return false;' value='$id' $checked >$span2";
        
        $span1='';
        $span2='';
        $checked='';
        if($studentRecordArray[$i]['other']==1) {
           $span1='<span style="background-color:RED">';
           $span2='</span>';
           $checked='checked=checked';
        }
        $other = "$span1<input type='checkbox' id='other_$id' name='chb3[]' onChange='getStatus($id,\"O\"); return false;' value='$id' $checked >$span2";
       
        
        $str = $studentName."!~~!".$univRollNo;
	    $studentList = "<input type='hidden' id='student_$id' name='hiddenChb[]' value='$str' readonly='readonly'>";
  
        $srNo = ($records+$i+1).$studentList;
        $valueArray = array_merge(array('srNo' => $srNo,
					'isDayScholar' => $dayScholar,
					'isHostler' => $hostler,
                    'isOther' => $other),
                    $studentRecordArray[$i]);

       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>    
