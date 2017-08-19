<?php
//-------------------------------------------------------
// Purpose: To design the Student Fee Concession Mapping     
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    define('MODULE','StudentFeeConcessionMapping');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	
    require_once(MODEL_PATH . "/StudentFeeConcessionMappingManager.inc.php");
    $studentFeeConcessionManager = StudentFeeConcessionMappingManager::getInstance();
    
    $feeClassId = add_slashes($REQUEST_DATA['feeClassId']);
    $studentName = add_slashes($REQUEST_DATA['studentName']);
    $rollNo = add_slashes($REQUEST_DATA['rollNo']);
   
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
    
    
    $checkCondition = " feeClassId = '$feeClassId'"; 
    $checkConcessionArray = $studentFeeConcessionManager->getCheckStudentConcession($checkCondition); 
    
     
    
	$studentRecordArray = $studentFeeConcessionManager->getStudentList($condition,'',$orderBy,$feeClassId);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $studentId = $studentRecordArray[$i]['studentId'];
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
        
        $condition = " AND fscm.studentId = $studentId AND fscm.classId = $feeClassId";
        $concessionCondition = " AND fh.isConsessionable = 1 ";
        $concessionArray = $studentFeeConcessionManager->getStudentConcessionCategoryList($condition,$concessionCondition);
        
        $selectName = "concessionCategory".$studentId."[]";
        $concessionCategory = "<select multiple name=$selectName id=concessionCategory".$studentId." class='inputbox1' style='width:300px' size='5'>";
        
        for($j=0;$j<count($concessionArray);$j++) {
           $id = $concessionArray[$j]['categoryId']; 
           $name = $concessionArray[$j]['categoryName'];
           $chkClassId = $concessionArray[$j]['classId'];
           $checked="";
           if($chkClassId!='') {
             $checked="selected=selected";  
           }
           $concessionCategory .= "<option $checked value=".$id.">".$name."</option>"; 
        }
        $concessionCategory .= "</select>
                                  <a class='allReportLink' href='javascript:makeSelection(\"".$selectName."\",\"All\",\"listForm\");'>All</a> / 
                                  <a class='allReportLink' href='javascript:makeSelection(\"".$selectName."\",\"None\",\"listForm\");'>None</a>
                                  <input type=\"hidden\" name=\"studentId[]\" id=\"studentId".$studentId."\" value=\"$studentId\" "; 
                
        $imgSrc = "<img src='".$imgSrc."' width='40' height='40' id='studentImageId' class='imgLinkRemove' />";
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        
        $find=0;
        for($j=0;$j<count($checkConcessionArray);$j++) {
           if($checkConcessionArray[$j]['studentId']==$studentId && $checkConcessionArray[$j]['feeClassId']==$feeClassId) {
              $find=1;     
              break; 
           } 
        }
        
        if($find==0) {
          $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentId).'">';
        }
        else {
          $concessionCategory = "Already Apply for Adhoc Concession";
          $checkall = NOT_APPLICABLE_STRING;
        }
        
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                        'checkAll' => $checkall,  
                                        'concessionCategory' => $concessionCategory),
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
