<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Ipta Thakur
// Created on : 12-10-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GradeTranscriptReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();  
                         
    require_once(MODEL_PATH . "/GradeTranscriptReportManager.inc.php");
    $gradeTranscriptReportManager = GradeTranscriptReportManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
   
    
    $batchId = add_slashes($REQUEST_DATA['batchId']);   
    $degreeId = add_slashes($REQUEST_DATA['degreeId']); 
    $branchId = add_slashes($REQUEST_DATA['branchId']); 
    $rollNo = add_slashes(trim($REQUEST_DATA['rollno']));
    $semesterId = add_slashes(trim($REQUEST_DATA['semesterId']));
   
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";    
     
     if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",ss.studentId, studentName)';
     }
     else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",ss.studentId, rollNo)';
     } 
     else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",ss.studentId, universityRollNo)';
     }
    
     $orderBy = " $sortField1 $sortOrderBy";    
     
     // $recordsPerPage = RECORDS_PER_PAGE;
     $recordsPerPage = 5000; 
     // to limit records per page    
     $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
     $records    = ($page-1)* $recordsPerPage;
     $limit      = ' LIMIT '.$records.','.$recordsPerPage;
     
     //this function will format number to $decimal places
     function formatTotal($input,$decimal=2){
        return number_format($input,1,'.','');
     }
     
    // Fetch Subject Detail
    $subjectCondition = "AND d.classId IN 
                         (SELECT 
                              DISTINCT cc.classId 
                          FROM 
                              class cc 
                          WHERE 
                              cc.branchId = '$branchId' AND cc.batchId = '$batchId' AND cc.degreeId = '$degreeId')"; 
    $subjectArray= $gradeTranscriptReportManager->getClassSubjectsWithOtherSubjects($subjectCondition);  
    

    // Fetch Subject List Class Wise Count Detail
    $subjectCondition = "AND d.classId IN 
                         (SELECT 
                              DISTINCT cc.classId 
                          FROM 
                              class cc 
                          WHERE 
                              cc.branchId = '$branchId' AND cc.batchId = '$batchId' AND cc.degreeId = '$degreeId')"; 
    $subjectCountArray= $gradeTranscriptReportManager->getSubjectsClassWiseCount($subjectCondition);  

    
    // Fetch Student Detail
    $conditions = " AND e.branchId = '$branchId' AND b.batchId = '$batchId' AND d.degreeId = '$degreeId'";
    if($rollNo!='') {
       $conditions .= " AND a.rollNo LIKE '$rollNo%' "; 
    }
    $conditions .=" GROUP BY ss.studentId";
    $studentArray= $gradeTranscriptReportManager->getAllDetailsStudentList($conditions, $orderBy, $limit);
    $cnt = count($studentArray);
    
    $rowspan='5';
    
    $tableClass='';
    for($i=0;$i<count($subjectCountArray);$i++) {
      $className = $subjectCountArray[$i]['className'];  
      $subjectCount = $subjectCountArray[$i]['subjectCount'];
      $colspan= $subjectCount*2;    
      $tableClass .="<td colspan='$colspan' class='searchhead_text' align='center'>
                        <strong><nobr>$className</nobr></strong>
                     </td>
                     <td rowspan='$rowspan' class='searchhead_text' align='center'>
                        <strong><nobr>Total</nobr></strong>
                     </td> ";                     
    }
     
  
    
    $tableSubject='';    
    $tableGrade ='';
    $tableCredit ='';	
    $tableSession='';
   
    for($i=0;$i<count($subjectArray);$i++) {
      $subjectName = $subjectArray[$i]['subjectName'];  
      $subjectCode = $subjectArray[$i]['subjectCode'];
      $credit = $subjectArray[$i]['credits'];
      $sessionName = $subjectArray[$i]['sessionName'];
      $total =  $credit*$result1;
		
      $tableSubject .="<td width='20px' class='searchhead_text' align='center' colspan='2'>
                        <strong><nobr>$subjectCode</nobr></strong>
                     </td> ";
      $tableCredit .="<td width='20px' class='searchhead_text' align='center' colspan='2'>
                        <strong><nobr>$credit</nobr></strong>
                     </td> ";

      $tableSession .="<td width='20px' class='searchhead_text' align='center' colspan='2'>
                        <strong><nobr>$sessionName</nobr></strong>
                     </td> ";
      $tableGrade .="<td width='20px' class='searchhead_text' align='center'>Final Grade</td>
                     <td width='20px' class='searchhead_text' align='center'>Letter Grade</td>";
     
    }
    
        
    $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'>
                      <td width='2px'  rowspan='$rowspan' class='searchhead_text' align='left'><b><nobr>#</nobr></b></td>
                      <td width='5px'  rowspan='$rowspan' class='searchhead_text' align='left'><strong><nobr>Roll No.</nobr></strong></td>
                      <td width='20px' rowspan='$rowspan' class='searchhead_text' align='left'><strong><nobr>Student Name</nobr></strong></td>
                      $tableClass
                    </tr>
                    <tr class='rowheading'>  
                       $tableSubject 
                    </tr>
                    <tr class='rowheading'>  
                       $tableCredit 
                    </tr>
		            <tr class='rowheading'>  
                       $tableSession 
                    </tr>
                    <tr class='rowheading'>  
                       $tableGrade 
                    </tr>";
                                        
   
    for($i=0;$i<count($studentArray);$i++) {
       $studentId = $studentArray[$i]['studentId'];
      
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $tableData .= "<tr class='$bg'>
                         <td valign='top' class='padding_top' align='left'>".($records+$i+1)."</td>  
                         <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                         <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>"; 
       
       $compareClassId='';
       $classWiseTotal='0';
       $find='0';
     
       
       for($j=0;$j<count($subjectArray);$j++) {      
          $subjectId = $subjectArray[$j]['subjectId'];
          $classId = $subjectArray[$j]['classId'];
          $credit = $subjectArray[$j]['credits'];  
          
          if($compareClassId=='') {
            $compareClassId = $classId; 
          }
          
          if($classId!=$compareClassId) {
             $tableData .="<td valign='top' class='padding_top' align='center'>$classWiseTotal</td>";
             $classWiseTotal='0';  
             $compareClassId = $classId; 
             $find='1'; 
          }
          
         
          if($classId==$compareClassId) {  
             $find='0'; 
          }
           
          $tableData .= getFinalGrade($studentId, $classId, $subjectId,$classWiseTotal,$credit); 
       }
       
       if($find=='0') {
         $tableData .="<td valign='top' class='padding_top' align='center'>$classWiseTotal</td>"; 
       }
       
       
       $tableData .= "</tr>"; 
    } 
     echo $tableData.'!~~!'.$totalStudent;
     die;
     
function getFinalGrade($studentId, $classId, $subjectId, $classWiseTotal, $credit) {
    
    
     global $commonQueryManager;
     global $gradeTranscriptReportManager;
     global $findClassId;
     global $classWiseTotal;
     
     $result='';
    
     
     $valueArray = array();    
     $i=0;
     
     $attCondition = " AND att.classId= '$classId' AND att.subjectId = '$subjectId' AND att.studentId = $studentId";
     $attOrderBy = " subjectCode ";
     $consolidated = "1";                                                              
     $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$attOrderBy,$consolidated);  
     
     
     $studentTestTypeCategoryArray=$gradeTranscriptReportManager->getStudentTestTypeCategoryCount($classId,$subjectId,$studentId);
     $ttcCount=count($studentTestTypeCategoryArray);
     for($j=0; $j< $ttcCount; $j++) {
       $testTypeName =  $studentTestTypeCategoryArray[$j]['testTypeName'];
     }
     
     
     
        $total="";    
        $maxMarks="";     
        //get test typa variations(how many sessionals,assignments etc)
        for($j=0; $j< $ttcCount; $j++) { 
          $testTypeCategoryId = $studentTestTypeCategoryArray[$j]['testTypeCategoryId'];       
          $ids = "test".$j;
          
          $categoryCondition =  " AND  ttc.testTypeCategoryId = $testTypeCategoryId ";
          //get test typa variations(how many sessionals,assignments etc)
          $testTypeCategoryArray=$gradeTranscriptReportManager->getTestTypeCategoryCount($studentId,$classId,$subjectId,$categoryCondition);
          $testTypeCategoryIds=UtilityManager::makeCSList($testTypeCategoryArray,'testTypeCategoryId');
       
          $testTypeIds = '-1';  
          if($testTypeCategoryIds!=''){
            //get the testypes corresponding to these categories
            $testTypeArray=$gradeTranscriptReportManager->getTestTypesDetails($testTypeCategoryIds);
            $testTypeIds =UtilityManager::makeCSList($testTypeArray,'testTypeId');
          }
          
          if($testTypeIds=='') {
            $testTypeIds = '-1';  
          }
          
          
          $condition = " AND tt.conductingAuthority = 1 AND tt.testTypeCategoryId = $testTypeCategoryId";
          $testTransferredMarksArray=$gradeTranscriptReportManager->getSubjectWiseTestTransferredMarks($testTypeIds,$studentId,$classId,$subjectId, $condition);
          if(count($testTransferredMarksArray)>0) {
             $tot = formatTotal($testTransferredMarksArray[0]['marksScored']).'/'.formatTotal($testTransferredMarksArray[0]['maxMarks']);
             $per = formatTotal(($testTransferredMarksArray[0]['marksScored']/$testTransferredMarksArray[0]['maxMarks'])*100);
             if($total=="") {
               $total=0;   
               $maxMarks=0;
             }
             $total = doubleval($total) + doubleval($tot);
             $maxMarks = doubleval($maxMarks) + doubleval($testTransferredMarksArray[0]['maxMarks']);
             $showGrade = $testTransferredMarksArray[0]['gradeLabel'];
             $showGradeValue = $testTransferredMarksArray[0]['gradePoints'];
          }                
        }
       
        if($total=="") {
          $valueArray[$i]['total'] = "";
        }
        else {
          $showGrade = $marksNotTransferredIndicator;  
          $showGradeValue = $marksNotTransferredIndicator;
          $condition = " AND ttm.studentId = $studentId AND ttm.classId = $classId AND ttm.subjectId = $subjectId ";
          $studentTotalMarksArray = $gradeTranscriptReportManager->getSubjectTransferredDetails($condition); 
          if(count($studentTotalMarksArray)>0) {
            $total = $studentTotalMarksArray[0]['marksScored'];    
            $showGrade =    $studentTotalMarksArray[0]['gradeLabel'];
            $showGradeValue =  $studentTotalMarksArray[0]['gradePoints'];            
          }
          $valueArray[$i]['grade'] = $showGrade;  
          $valueArray[$i]['point'] = $showGradeValue; 
        }     
       
        $find=0; 
        for($att=0;$att<count($studentAttendanceArray);$att++) {          
           if($studentAttendanceArray[$att]['studentId'] == $studentId) {
             $find=1;  
             $attended = $studentAttendanceArray[$att]['attended'];
             $delivered = $studentAttendanceArray[$att]['delivered'];
             $leaveTaken = $studentAttendanceArray[$att]['leaveTaken'];
             $valueArray[$i]['attended'] = 0;  
             $valueArray[$i]['delivered'] = 0;  
             $valueArray[$i]['dutyLeave'] = 0;  
             $valueArray[$i]['percentage'] = 0;
             if($delivered=='0') {
                $tot = 0;  
                $per = 0;
             } 
             else {
                $tot = ($attended+$leaveTaken).'/'.$delivered;
                $per = formatTotal((($attended+$leaveTaken)/$delivered*100));  
                $valueArray[$i]['attended'] = $attended;  
                $valueArray[$i]['delivered'] = $delivered;  
                $valueArray[$i]['dutyLeave'] = $leaveTaken;  
                $valueArray[$i]['percentage'] = $per;  
             }
             if($tot==0) {
               $result=0; 
             }
             else {
                if($incMarksPer==1) { 
                  $result = "$tot ($per%)";
                }
                else {
                  $result = "$per";
                }
             }
             break;
           }
        }
        if($find==0) {
          $valueArray[$i]['attended'] = "";  
          $valueArray[$i]['delivered'] = "";  
          $valueArray[$i]['dutyLeave'] = "";  
          $valueArray[$i]['percentage'] = ""; 
        }
        $per = $valueArray[$i]['percentage'];
        $conditionAttGradeDeduct = " AND ('$per' BETWEEN minval AND maxval) "; 
        $attendanceGradeDeductArray = $gradeTranscriptReportManager->getAttendanceGradeDeduct($conditionAttGradeDeduct);  
        $valueArray[$i]['gradeDeduct'] = "";  
        $valueArray[$i]['attendanceDeductId'] = "";
        if(is_array($attendanceGradeDeductArray) && count($attendanceGradeDeductArray)>0 ) { 
           $valueArray[$i]['gradeDeduct'] = $attendanceGradeDeductArray[0]['point']; 
           $valueArray[$i]['attendanceDeductId'] = $attendanceGradeDeductArray[0]['attendanceGradeId'];       
        }
        $finalPoint = "";
        if($valueArray[$i]['gradeDeduct']=='') {
          $finalPoint = $valueArray[$i]['point'];
        }
        else {
          $finalPoint = doubleval($valueArray[$i]['point'])-doubleval($valueArray[$i]['gradeDeduct']);  
        }
        $valueArray[$i]['finalPoint'] = $finalPoint;  
        $result .= "<td valign='top' class='padding_top' align='center'>".$finalPoint."</td>"; 
       
        $finalPoint = $valueArray[$i]['finalPoint'];
        $conditionCGPAGrade = " AND ('$finalPoint' BETWEEN minval AND maxval) "; 
        $finalCGPAArray = $gradeTranscriptReportManager->getStudentFinalCGPAGrade($conditionCGPAGrade); 
        $valueArray[$i]['finalGrade'] = "I";  
        if(is_array($finalCGPAArray) && count($finalCGPAArray)>0 ) { 
          $valueArray[$i]['finalGrade'] = $finalCGPAArray[0]['grade'];                         
        }
        $result = "<td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalPoint']."</td>
                   <td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalGrade']."</td>";
                   
       $classWiseTotal += ($valueArray[$i]['finalPoint']*$credit);            
                   
      return $result;
}    
?>
