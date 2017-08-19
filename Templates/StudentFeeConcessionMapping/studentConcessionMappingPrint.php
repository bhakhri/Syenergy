 <?php 
//This file is used as CSV version for display countries.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentFeeConcessionMapping');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    
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
  
  
    $studentRecordArray = $studentFeeConcessionManager->getClassName($feeClassId);
    $search  = "<b>Class&nbsp;:&nbsp;</b>".$studentRecordArray[0]['className'];
    if($studentName!='') {
      $search .= "<br><b>Name&nbsp;:&nbsp;</b>".$studentName;
    }
    if($rollNo!='') {
      $search .= "<br><b>Roll No.&nbsp;:&nbsp;</b>".$rollNo;
    }
    
    $studentRecordArray = $studentFeeConcessionManager->getStudentList($condition,'',$orderBy,$feeClassId);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $studentId = $studentRecordArray[$i]['studentId'];
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
                  
        $condition = " AND fscm.studentId = $studentId AND fscm.classId = $feeClassId";
        $concessionCondition = " AND fh.isConsessionable = 1 ";
        $concessionArray = $studentFeeConcessionManager->getStudentConcessionCategoryList($condition,$concessionCondition);
        $concessionCategory = '';
        for($j=0;$j<count($concessionArray);$j++) {
           $name = $concessionArray[$j]['categoryName'];
           $chkClassId = $concessionArray[$j]['classId'];
           if($chkClassId!='') {
             if($concessionCategory != '') {  
               $concessionCategory .="<br>";
             } 
             $concessionCategory .= $name; 
           }
        }
        if($concessionCategory == '') {  
          $concessionCategory = NOT_APPLICABLE_STRING;
        } 
                
        $imgSrc = "<img src='".$imgSrc."' width='40' height='40' id='studentImageId' class='imgLinkRemove' />";
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        
        $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentId).'">';
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1),
                                          'concessionCategory' => $concessionCategory), $studentRecordArray[$i]);
    }
        
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Fee Concession Mapping');
	$reportManager->setReportInformation($search);
	

    $reportTableHead                        =    array();
                    //associated key                  col.label,                      col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                         ' width="2%"  align="left"', "align='left'");
    $reportTableHead['studentName']			=    array('Student Name',              ' width=15%   align="left" ','align="left" ');
    $reportTableHead['rollNo']	            =    array('Roll No.',                  ' width="12%" align="left" ','align="left"');
	$reportTableHead['universityRollNo']	=    array('Univ. No.',                 ' width="12%" align="left" ','align="left"');
    $reportTableHead['regNo']               =    array('Reg. No.',                  ' width="12%" align="left" ','align="left"');
    $reportTableHead['imgSrc']              =    array('Photo',                     ' width="12%" align="center" ','align="center"');
    $reportTableHead['concessionCategory']  =    array('Fee Concession Category',   ' width="25%" align="left" ','align="left"');  

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
?>
