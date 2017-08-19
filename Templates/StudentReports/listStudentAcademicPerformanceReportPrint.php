<?php 
//This file is used as printing version for attendance report.
//
// Author :Aditi Miglani
// Created on : 15-Dec-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentAcademicPerformanceReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1); 
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    global $sessionHandler;  
    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance();

    $studentReportsManager = StudentReportsManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    $chb     = $REQUEST_DATA['chb'];
    $classId = $REQUEST_DATA['degree'][0];
    $timeTableLabelName=trim($REQUEST_DATA['timeTabelLabelName']);
    $marksTransferredSubjectsArray = array();

    
    
    if($classId=='' or $chb='' or $timeTableLabelName==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    
    /****************************Hard Coded Values**************************************************/
    if(trim($REQUEST_DATA['incDetained'])==1){
        $theoryLimit=trim($REQUEST_DATA['incTheory'])!=''?trim($REQUEST_DATA['incTheory']):0;
        $practicalLimit=trim($REQUEST_DATA['incPractical'])!=''?trim($REQUEST_DATA['incPractical']):0;
        $trainingLimit=trim($REQUEST_DATA['incTraining'])!=''?trim($REQUEST_DATA['incTraining']):0;
    }
    else {
        $theoryLimit=0;
        $practicalLimit=0;
        $trainingLimit=0;
    }
    
    $incTotal=trim($REQUEST_DATA['incTotal']);
    $showPercentage=trim($REQUEST_DATA['showPercentage']);
    $showHODInfo=trim($REQUEST_DATA['showHODInfo']);
    $showTransfer=trim($REQUEST_DATA['showTransfer']);
    
    if($showTransfer=='') {
      $showTransfer=0;  
    }
    
    //fetch class name
    $classInfoArray=$studentReportsManager->getClassInfo($classId);
    if(trim($classInfoArray[0]['className'])==''){
        $className=NOT_APPLICABLE_STRING;
    }
    else{
        $className=trim($classInfoArray[0]['className']);
    }
    
    /****************************Hard Coded Values**************************************************/
    
    //$attThreshold=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
    $attThreshold=0;
    $attendanceShortIndicator='*';
    $marksNotTransferredIndicator='MNT';
    //this function will format number to $decimal places
    function formatTotal($input,$decimal=2){
        return number_format($input,1,'.','');
    }
    
    
    
    //generating report header and footer
    echo  $reportHeader='
                    <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
                        <tr>
                            <td align="left" colspan="1" width="25%" class="">'.$reportManager->showHeader().'</td>
                            <th align="center" colspan="1" width="50%" '.$reportManager->getReportTitleStyle().'>
                            '.$reportManager->getInstituteName().'</th>
                            <td align="right" colspan="1" width="25%" class="">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("d-M-y").'</td>
                                    </tr>
                                    <tr>
                                        <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right">Time :&nbsp;</td><td valign="" colspan="1" '. $reportManager->getDateTimeStyle().'>'.date("h:i:s A").'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><th colspan="3" '.$reportManager->getReportHeadingStyle().' align="center">SUBJECT : STUDENT ACADEMIC PERFORMANCE REPORT ('.$timeTableLabelName.' )</th></tr>
                        </table> <br>';
                        
      $reportFooter='<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
                        <tr>
                            <td align="left" colspan="8" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
                        </tr>
                        </table>'; 
    //generating report header and footer

    //get the subject types corresponding to this class
    $subjectTypeArray=$studentReportsManager->getSubjectTypeDetails($classId);
    $subjectTypeCnt=count($subjectTypeArray);
    
    if($subjectTypeCnt==0){
        echo '<table border="1" cellpadding="0" cellspacing="0" width="90%"  class="reportTableBorder"  align="center">
                   <tr><td align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>
             </table>';
        echo $reportFooter;
        die;
    }
    
    //*******get the studentIds
    $studentIds=implode(',',$REQUEST_DATA['chb']);
    
    //get the student's personal information
    $studentInfoArray=$studentReportsManager->getStudentInfo($studentIds);
    $studentCnt=count($studentInfoArray);
    
    //code for address  
    $addressFlag=trim($REQUEST_DATA['addressChk']);
    if($addressFlag!=0){
        $studentAddressArray=$studentReportsManager->getStudentAddress($studentIds);
        $studentPermAddessArray=array();
        $studentCorrAddessArray=array();
        if(is_array($studentAddressArray) and count($studentAddressArray)>0){
            foreach($studentAddressArray as $key=>$val){
               $studentPermAddessArray[$studentAddressArray[$key]['studentId']]=
                $studentAddressArray[$key]['fatherName'].'<br/>'.$studentAddressArray[$key]['permAddress1'].'<br/>'.$studentAddressArray[$key]['permAddress2'].','.$studentAddressArray[$key]['permanantCity'].'<br/>'.$studentAddressArray[$key]['permanantState'];
               $studentCorrAddessArray[$studentAddressArray[$key]['studentId']]=
                $studentAddressArray[$key]['fatherName'].'<br/>'.$studentAddressArray[$key]['corrAddress1'].'<br/>'.$studentAddressArray[$key]['corrAddress2'].','.$studentAddressArray[$key]['corrCity'].'<br/>'.$studentAddressArray[$key]['corrState'];
            }
        }
    }
    
    $totalMarksCount=0;
    
    
    for ($i=0;$i<$studentCnt;$i++) { //looping through students
      
      if($i>0){
          echo $reportHeader;
      }
      $studentId = $studentInfoArray[$i]['studentId'];
      
      echo '<table border="0" cellpadding="0" cellspacing="0" width="90%" class="reportTableBorder"  align="center">';
      echo '<tr><td height="5px"></td></tr>';
      //echo '<tr><td align="center" '.$reportManager->getReportDataStyle().'>The Internal performance of <b>'.strtoupper($studentInfoArray[$i]['studentName']).'</b> , college roll no. <b>'.$studentInfoArray[$i]['rollNo'].'</b> a student of <b>'.$studentInfoArray[$i]['semName'].'</b> of <b>'.$studentInfoArray[$i]['stremName'].'</b> in the current semester is appended below</td></tr>';
      echo '<tr><td align="center" '.$reportManager->getReportDataStyle().'>Performance of <b>'.ucwords((strtolower($studentInfoArray[$i]['studentName']))).'</b> Univ. Roll No. <b>'.$studentInfoArray[$i]['universityRollNo'].'</b> is shown below</td></tr>';
      
      if($addressFlag!=0){
          $permAddress=trim($studentPermAddessArray[$studentId]);
          if($permAddress=='<br/>'){
              $permAddress=NOT_APPLICABLE_STRING;
          }
          $corrAddress=trim($studentCorrAddessArray[$studentId]);
          if($corrAddress=='<br/>'){
              $corrAddress=NOT_APPLICABLE_STRING;
          }
          
          if($addressFlag==1){
              if($permAddress!=NOT_APPLICABLE_STRING){
                echo '<tr><td align="left" valign="top" '.$reportManager->getReportDataStyle().'>'.$permAddress.'</td></tr>';
              }
              else{
                echo '<tr><td align="left" valign="top" '.$reportManager->getReportDataStyle().'>'.$corrAddress.'</td></tr>';
              }
          } 
          if($addressFlag==2){
              if($corrAddress!=NOT_APPLICABLE_STRING){
                echo '<tr><td align="left" valign="top" '.$reportManager->getReportDataStyle().'>'.$corrAddress.'</td></tr>';
              }
              else{
                echo '<tr><td align="left" valign="top" '.$reportManager->getReportDataStyle().'>'.$permAddress.'</td></tr>';
              }
          }
      }
      
      echo '<tr><td>';
      
      
      $studentAttendanceArray=$studentReportsManager->getStudentAttendanceDate($classId,$studentId);  
      $lastAttendanceDate = UtilityManager::formatDate($studentAttendanceArray[0]['toDate']);
      
      echo '<tr><td align="left" '.$reportManager->getReportDataStyle().'>
                <br>Dear Parents/ Guardians,<br><br>
                <div align="justify">
                 It is to inform you that your ward <b><u>'.trim(ucwords((strtolower($studentInfoArray[$i]['studentName'])))).'</u></b> 
                 Univ. Roll No <b><u>'.trim($studentInfoArray[$i]['universityRollNo']).'</u></b> a student of  class <b><u>'.$className.'</u></b> 
                 has obtained the marks in various subjects in Sessional examination conducted by college as given below.<br><br>
                 His/ Her attendance in these subjects upto <b><u>'.trim($lastAttendanceDate).'</u></b> is also given for your information.';
           if(trim($REQUEST_DATA['incDetained'])!=1){         
             echo nl2br($sessionHandler->getSessionVariable('STUDENT_DETAINED_MESSAGE'));
           } 
           echo ' You may personally contact his/ her HOD for further information.<br><br>
                 </div>
                </td></tr>'; 
      
      $charArray = array(); 
      $m=0; 
      
      $chartMarksArray= array(); 
      $mm=0; 
      
      for($stc=0;$stc<$subjectTypeCnt;$stc++) {//looping through subject types
        
         $filterType  = " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
         $orderByType = " subjectCode "; 
         $cond = " AND c.classId = '$classId' AND su.subjectTypeId = ".$subjectTypeArray[$stc]['subjectTypeId']; 
         $tempSubjectArray =  $studentReportsManager->getAllSubjectAndSubjectTypes($cond, $filterType, '',  $orderByType);
         $getSubjectIds = UtilityManager::makeCSList($tempSubjectArray,'subjectId');
         
         if($getSubjectIds=='') {
           $getSubjectIds=0;  
         }
        
        
         if($getSubjectIds!=0) {   
             // get student attendance 
             $attCondition = " AND att.classId= '$classId' AND att.studentId = '$studentId' ";
             $attOrderBy = " subjectCode ";
             $consolidated = "1";
             $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$attOrderBy,$consolidated); 
            
             //get test typa variations(how many sessionals,assignments etc)
             $studentTestTypeCategoryArray=$studentReportsManager->getTestTypeCategoryCount($studentId,$classId,$getSubjectIds);
             $ttcCount=count($studentTestTypeCategoryArray);
             $testTypeCategoryIds=UtilityManager::makeCSList($studentTestTypeCategoryArray,'testTypeCategoryId');
           
             if($testTypeCategoryIds!=''){
               //get the testypes corresponding to these categories
               $testTypeArray=$studentReportsManager->getTestTypesDetails($testTypeCategoryIds);
               $testTypeIds =UtilityManager::makeCSList($testTypeArray,'testTypeId');
             }
             
             //get test detaisl(name,max marks,marks scored etc)
             $studentTestMarksArray=$studentReportsManager->getTestMarksDetails($studentId,$classId,$getSubjectIds);
             $actualMarksArray = array();
             foreach ($studentTestMarksArray as $record) {
               /* if(!isset($subjectTestCountArray[$record['subjectId']][$record['testTypeCategoryId']])) {
                    $subjectTestCountArray[$record['subjectId']][$record['testTypeCategoryId']] = 0;
                  }
                  $subjectTestCountArray[$record['subjectId']][$record['testTypeCategoryId']]++;
               */ 
               $actualMarksArray[$record['subjectId']][$record['testTypeCategoryId']][$record['testIndex']] = $record['marksScored'] . '/' . $record['maxMarks'];
             }
             
             $tdGrandTotal = "";
             $tdAttendance = "";
             $colspanAttendance=2;
             if($incTotal==1) {
               $colspanAttendance++;
               if($showTransfer==1) {    
                 $tdGrandTotal .= '<td rowspan="2" align="right" '.$reportManager->getReportDataStyle().'><b>Grand<br>Total Marks</b></td>';
                 $tdAttendance .= '<td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>Marks</b></td>';
               }
               else {
                 $tdAttendance .= '<td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>Total</b></td>';
               }
             }
             if($showPercentage==1) {
                $colspanAttendance++;
                if($showTransfer==1) {    
                  $tdGrandTotal .= '<td rowspan="2" align="right" '.$reportManager->getReportDataStyle().'><b>Grand %age</b></td>';
                }
                $tdAttendance .= '<td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>%age</b></td>';
             }
         
         
             //starting subject type table
             echo '<table border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
                   <tr><td '.$reportManager->getReportDataStyle().'><b>'.$subjectTypeArray[$stc]['subjectTypeName'].' :</b></td></tr>
                   <tr><td height="5px"></td></tr>
                   <tr><td width="100%">';  
             echo '<table border="1" cellspacing="0" cellpadding="0" width="100%"  class="reportTableBorder"  align="left">
                     <tr>
                        <td rowspan="2" align="left"  class = "headingFont" width="2%" style="padding-left:2px">#</td>
                        <td rowspan="2" align="left" '.$reportManager->getReportDataStyle().' width="8%" style="padding-left:2px"><b>Sub. Code</b></td>
                        <td rowspan="2" align="left"  '.$reportManager->getReportDataStyle().' width="20%" style="padding-left:2px"><b>Subject Name</b></td>
                        <td colspan="'.$colspanAttendance.'" align="center" '.$reportManager->getReportDataStyle().'><b>Attendance</b></td>';
            
              $tdTestType='';            
              $studentTestArray = array();
              for($j=0; $j<count($studentTestTypeCategoryArray); $j++) {
                  $testTypeName =  $studentTestTypeCategoryArray[$j]['testTypeName']; 
                  $categoryCount = $studentTestTypeCategoryArray[$j]['categoryCount'];
                  $testTypeAbbr = $studentTestTypeCategoryArray[$j]['testTypeAbbr'];
                  for($tt=0;$tt<$categoryCount;$tt++) {
                    $showTestTypeAbbr = $testTypeAbbr."-".($tt+1);  
                    $tdTestType .= '<td  align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>'.$showTestTypeAbbr.'</b></td>';
                    $studentTestArray[] = $showTestTypeAbbr;
                  }
                  if($incTotal==1 && $categoryCount > 0 ) {
                     $categoryCount=$categoryCount+1;
                     if($showTransfer==1) { 
                       $tdTestType .= '<td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>Marks</b></td>';
                     }
                     else {
                       $tdTestType .= '<td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>Total</b></td>';  
                     }
                  }
                  if($showPercentage==1 && $categoryCount > 0 ) {
                    $categoryCount=$categoryCount+1;
                    $tdTestType .= '<td  align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>%age</b></td>';
                  }
                  
                  echo '<td colspan="'.$categoryCount.'" align="center" '.$reportManager->getReportDataStyle().'><b>'.$testTypeName.'</b></td>';
             }       
             echo $tdGrandTotal."</tr>";
             
             echo '<tr>
                     <td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>Lect. Held</b></td>
                     <td align="right" '.$reportManager->getReportDataStyle().' width="8%"><b>Lect. Attended</b></td>';
             echo $tdAttendance;  
             echo $tdTestType;
             echo '</tr>';
         
        
             for($su=0; $su<count($tempSubjectArray); $su++) {
                  $subjectName = $tempSubjectArray[$su]['subjectName'];  
                  $subjectCode = $tempSubjectArray[$su]['subjectCode'];
                  $subjectId = $tempSubjectArray[$su]['subjectId'];
                  echo '<tr>
                            <td align="left" '.$reportManager->getReportDataStyle().'>'.($su+1).'</td>
                            <td align="left" '.$reportManager->getReportDataStyle().'>'.$subjectCode.'</td>
                            <td align="left" '.$reportManager->getReportDataStyle().'>'.$subjectName.'</td>';
                            
                  // Get Student Attendance 
                  $find=0; 
                  for($att=0;$att<count($studentAttendanceArray);$att++) {          
                      if($subjectId == $studentAttendanceArray[$att]['subjectId']) {
                         $attended = $studentAttendanceArray[$att]['attended'];
                         $delivered = $studentAttendanceArray[$att]['delivered'];
                         $leaveTaken = $studentAttendanceArray[$att]['leaveTaken'];
                         $medicalLeaveTaken = $studentAttendanceArray[$att]['transferMarksMedicalLeaveTaken'];
                         $subjectTypeId = $studentAttendanceArray[$att]['subjectTypeId']; 
                         if($delivered=='0' ) { 
                           $attended=0;  
                           $delivered=0;
                         }
                         if($showTransfer == 0) {
                            $tot = ($attended+$leaveTaken+$medicalLeaveTaken).'/'.$delivered;
                            $per = formatTotal((($attended+$leaveTaken+$medicalLeaveTaken)/$delivered*100));
                            if($delivered=='0') {
                              $tot = 0;  
                              $per = 0;
                            } 
                         }
                         else if($showTransfer == 1) {
                            $tot = $marksNotTransferredIndicator;  
                            $per = $marksNotTransferredIndicator;
                            $condition = " AND ttm.conductingAuthority = 3";
                            $studentTotalMarksArray = $studentReportsManager->getStudentTotalTransferredMarks($classId,$studentId,$subjectId,$condition); 
                            if(count($studentTotalMarksArray)>0) {
                               $tot = formatTotal($studentTotalMarksArray[0]['marksScored']).'/'.formatTotal($studentTotalMarksArray[0]['maxMarks']);
                               $per = formatTotal(($studentTotalMarksArray[0]['marksScored']/$studentTotalMarksArray[0]['maxMarks'])*100);                          
                            }
                         }
                         
                         $lecAttended =($attended+$leaveTaken+$medicalLeaveTaken);
                         $lecDelivered =$delivered;  
                         $attTotal = $tot;
                         $attPer = $per;
                         
                         if($subjectTypeId==1){
                           if($per != $marksNotTransferredIndicator) {  
                             if($per<$theoryLimit){
                               $lecAttended ='<b><u>'.($attended+$leaveTaken+$medicalLeaveTaken).'</u></b>';
                               $lecDelivered ='<b><u>'.$delivered.'</u></b>';
                               $attTotal ='<b><u>'.$tot.'</u></b>';
                               $attPer ='<b><u>'.$per.'</u></b>';
                             }
                           }
                         }
                         else if($subjectTypeId==2){
                           if($per != $marksNotTransferredIndicator) {  
                             if($per<$practicalLimit){
                               $lecAttended ='<b><u>'.($attended+$leaveTaken+$medicalLeaveTaken).'</u></b>';
                               $lecDelivered ='<b><u>'.$delivered.'</u></b>';
                               $attTotal ='<b><u>'.$tot.'</u></b>';
                               $attPer ='<b><u>'.$per.'</u></b>';
                             }
                           }  
                         }
                         else if($subjectTypeId==3){
                           if($per != $marksNotTransferredIndicator) {  
                             if($per<$trainingLimit){
                               $lecAttended ='<b><u>'.($attended+$leaveTaken+$medicalLeaveTaken).'</u></b>';
                               $lecDelivered ='<b><u>'.$delivered.'</u></b>';
                               $attTotal ='<b><u>'.$tot.'</u></b>';
                               $attPer ='<b><u>'.$per.'</u></b>';
                             }
                           } 
                         }
                         
                        $charArray[$m]['subjectName'] = $subjectName;
                        $charArray[$m]['subjectCode'] = $subjectCode;
                        $charArray[$m]['subjectId'] = $subjectId;   
                        $charArray[$m]['percentage'] = 0;
                        if($per != $marksNotTransferredIndicator) {  
                          $charArray[$m]['percentage'] = $per;
                        }
                        $m++;
                        
                        echo '<td align="right" '.$reportManager->getReportDataStyle().'>'.$lecDelivered.'</td>
                               <td align="right" '.$reportManager->getReportDataStyle().'>'.$lecAttended.'</td>';
                         
                         if($incTotal==1) {
                            echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$attTotal.'</td>';
                         }
                         if($showPercentage==1) {
                            echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$attPer.'</td>'; 
                         }      
                         $find=1;
                         break;
                      }        
                  }
                  if($find==0) {
                     echo '<td colspan="'.$colspanAttendance.'" align="center" '.$reportManager->getReportDataStyle().'>'.NOT_APPLICABLE_STRING.'</td>';
                  }
                  
                  for($j=0; $j<count($studentTestTypeCategoryArray); $j++) {
                      $testTypeCategoryId =  $studentTestTypeCategoryArray[$j]['testTypeCategoryId']; 
                      $categoryCount = $studentTestTypeCategoryArray[$j]['categoryCount']; 
                      $tot=0;
                      $mm=0;
                      $per=0;
                      $find=0;
                      for($tt=1;$tt<=$categoryCount;$tt++) {
                         $mks = $actualMarksArray[$subjectId][$testTypeCategoryId][$tt];
                         if($mks=='') {
                            $mks = NOT_APPLICABLE_STRING;
                         }
                         else {
                            $markArray = explode('/',$mks); 
                            $tot += $markArray[0];
                            $mm += $markArray[1];
                            $find=1;   
                         }                                                                      
                         echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$mks.'</td>'; 
                      }
                      if($showTransfer == 0) {   
                         if($mm==0) {
                            $result = 0; 
                            $per = 0; 
                         } 
                         else {
                            $result = formatTotal($tot).'/'.formatTotal($mm);  
                            $per = formatTotal(($tot/$mm)*100);    
                         }
                      }
                      else {
                          $result = $marksNotTransferredIndicator;  
                          $per = $marksNotTransferredIndicator;
                          $condition = " AND tt.conductingAuthority = 1 AND tt.testTypeCategoryId = $testTypeCategoryId";
                          $testTransferredMarksArray=$studentReportsManager->getSubjectWiseTestTransferredMarks($testTypeIds,$studentId,$classId,$subjectId, $condition);
                          if(count($testTransferredMarksArray)>0) {
                            $result = formatTotal($testTransferredMarksArray[0]['marksScored']).'/'.formatTotal($testTransferredMarksArray[0]['maxMarks']);
                            $per = formatTotal(($testTransferredMarksArray[0]['marksScored']/$testTransferredMarksArray[0]['maxMarks'])*100);
                          }
                      }      
                      if($incTotal==1) {
                         if($find==0) { 
                            $result = NOT_APPLICABLE_STRING;   
                         }
                         echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$result.'</td>';
                      }
                      if($showPercentage==1) {
                         if($find==0) { 
                            $per = NOT_APPLICABLE_STRING;    
                         } 
                         echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$per.'</td>'; 
                      }        
                  }  
                  if($showTransfer == 1) { 
                     $result = $marksNotTransferredIndicator;  
                     $per = $marksNotTransferredIndicator;
                     $studentTotalMarksArray = $studentReportsManager->getStudentTotalTransferredMarks($classId,$studentId,$subjectId); 
                     if(count($studentTotalMarksArray)>0) {
                       $result = formatTotal($studentTotalMarksArray[0]['marksScored']).'/'.formatTotal($studentTotalMarksArray[0]['maxMarks']);
                       $per = formatTotal(($studentTotalMarksArray[0]['marksScored']/$studentTotalMarksArray[0]['maxMarks'])*100);                          
                     }
                     if($incTotal==1) {
                        echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$result.'</td>';
                     }
                     if($showPercentage==1) {
                        echo '<td align="right" '.$reportManager->getReportDataStyle().' width="8%">'.$per.'</td>'; 
                     } 
                  }
                  echo '</tr>';     
         }
         echo '</table></td></tr>
                <tr><td height="5px"></td></tr></table>';
       }
    }

    if(trim($REQUEST_DATA['graphAtt'])==1) {
      echo getChartGenerate($charArray);
    }

    $condition=" AND c.classId = ".$classId;
    //fetch hod info
    if($showHODInfo==1){
     $hodArray = $studentReportsManager->getHODInfo($condition);
    }
    
     echo '</td></tr></table>';
     echo '<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">';
     if(trim($REQUEST_DATA['incDetained'])==1){
       echo '<tr><td colspan="8" height="5px"></td></tr><tr>
                <td align="left" colspan="8" '.$reportManager->getReportDataStyle().'><b>Note</b> : Eligibility for Examination : A student has to attend '.$theoryLimit.'% in Theory, '.$practicalLimit.'% in Practical, '.$trainingLimit.'% in Training of the scheduled lectures.Otherwise he/she shall be detained in the
                University Examination.A student detained in a course(s) would be allowed to appear in the subsequesnt university exams only on having completed the attendance in the subject,when the course is offered as regular course(s).</td>
             </tr>
             <tr><td colspan="8" height="5px"></td></tr>';  
     }
     
     $classAdvisor = '';
     if($sessionHandler->getSessionVariable('Class_Advisor')!='') {
        $classAdvisor = 'Class Advisor'; 
     }
     
     if($attCount>0){
         echo '<tr>
                <td align="left" colspan="8" '.$reportManager->getFooterStyle().'>'.$attendanceShortIndicator.'&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;Attendance Short</td>
               </tr>'; 
     }
         echo '<tr>
                <td align="left" colspan="8" '.$reportManager->getFooterStyle().'>'.NOT_APPLICABLE_STRING.'&nbsp;  : &nbsp;Marks not available</td>
               </tr>';
         echo '<tr>
                <td align="left" colspan="8" '.$reportManager->getFooterStyle().'>'.$marksNotTransferredIndicator.'&nbsp;  : &nbsp;Marks not transferred</td>
               </tr>';      
               
        
        if($showHODInfo==1){
        echo '<tr>
                   <td align="left"  colspan="5" '.$reportManager->getReportDataStyle().'><b>HOD Information :</b></td>
                   <td align="right" colspan="5" '.$reportManager->getReportDataStyle().'><b>'.$classAdvisor.'</b></td>
               </tr>
               <tr>
                  <td align="left" valign="top" style="padding-bottom:5px" colspan="5" '.$reportManager->getReportDataStyle().'><nobr>
                    <table border="0" cellspacing="0" cellpadding="0" align="left" valign="top" width="40%">
                    <tr><td height="5px"></td></tr>';
                    for($h=0;$h<count($hodArray);$h++) {
                        if($h!=0) {
                          //echo "<br style='margin:8px;'>"; 
                        }
                        $departmentName  = $hodArray[$h]['departmentName'];     
                        $employeeName = $hodArray[$h]['employeeName'];  
                        $mobileNumber = $hodArray[$h]['mobileNumber'];  
                        if($mobileNumber!='') {
                          $employeeName .=",";
                          $mobileNumber1 = "Mob.: ".$mobileNumber;
                        }
                        echo "<tr><td valign='top'>".strtoupper($employeeName).'&nbsp;&nbsp;'.$mobileNumber1
                             .'<br>'.strtoupper($departmentName)."</td></tr>
                             <tr><td height='2px'><hr></td></tr>";
                     }      
            echo '</table></nobr>
                  </td>
                  <td align="right" valign="top" colspan="5" '.$reportManager->getReportDataStyle().'>
                     <nobr>'.$sessionHandler->getSessionVariable('Class_Advisor').'</nobr>
                  </td>
               </tr>';
        }
        
        echo '<tr><td height="15px"></td></tr>
              <tr>';    
        if(trim($REQUEST_DATA['signatureChk'])==1) {
           $signature = trim($REQUEST_DATA['signatureContents']); 
           echo '<td align="left" colspan="7" '.$reportManager->getFooterStyle().'>'.$signature.'</td>';    
        }
        else {
               echo '<td align="left" colspan="7" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>';
        }
        if(trim($REQUEST_DATA['incPage'])==1){//if user wants to view page numbers
           echo '<td align="right" colspan="1" '.$reportManager->getFooterStyle().'>Page '.($i+1).' / '.$studentCnt.'</td>';
        }                  
        echo '</tr>
            </table><br class="page" />';
              
    }


function getChartGenerate($charArray) {
    
     global $reportManager;
    
     $chartResult ='';
     $chartResult1 ='';
     $chartResult2 ='';
     
     
     for($j=0;$j<count($charArray);$j++) {
        $color = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
        $percentage = $charArray[$j]['percentage'];               
        $subjectName = $charArray[$j]['subjectName'];
        if($percentage=='0') {
          $percentage1 = '';  
          $percentage  ='';
        } 
        else {
          $msg = "alt='$subjectName ($percentage%)' title='$subjectName ($percentage%)'";
          $per = (int)$percentage;
          
          $perpx = (int)($per*250)/100;
          $style = "style='width:10px;height:$perpx;'"; 
          
          $fileName = HTTP_PATH."/Storage/Images/bar.gif?g=".rand(500,0);
          $img = "<img src='".$fileName."' width='20px' height='$perpx' $msg>";
          $percentage1 = "<div>$percentage</div><div>$img</div>"; 
          $percentage2 = "$percentage 
                          <table border='1' $style cellpadding='0' cellspacing='0' valign='bottom'>
                            <tr>
                               <td  align='center' valign='bottom' $style >&nbsp;</td>
                            </tr>
                         </table>";
        }    
        $chartResult1 .= "<td ".$reportManager->getReportDataStyle()." rowspan='11' valign='bottom' height='100%' align='center'>$percentage1</td>";
        $chartResult2 .= "<td ".$reportManager->getReportDataStyle()." align='center'>".$charArray[$j]['subjectCode']."</td>";
     }
     $cntColSpan = '';
     if(count($charArray)>0) {
       $cntColSpan = "colspan='".(count($charArray)+1)."'";
     }
     
     $chartResult  =  "
     <br>
     <table border='0' cellpadding='0' cellspacing='0'  width='80%' class='reportTableBorder'  align='center'>
     <tr>
     <td ".$reportManager->getReportDataStyle()." colspan=2 align=center style='padding-left:90px'>
     <b>Attendance Percentage Graph</b>
     </td>
     </tr>
     <td ".$reportManager->getReportDataStyle()." valign='middle' align='right' style='padding-right:10px'><b>Percentage</b></td> 
     <td>
         <table border='1' cellpadding='0' cellspacing='0'  width='100%' class='reportTableBorder'>";
         for($i=100;$i>=0;$i-=10) {
             $cc=$i;
             if($i==0) {
               $cc=1;  
             }
             $chartResult .="<tr>
                               <td valign='bottom' height='25px' ".$reportManager->getReportDataStyle()." >".$cc."</td>";
             if($i==100) {                    
              $chartResult .= $chartResult1;
             }
             $chartResult .= "</tr>";
         }
    
     $chartResult .= "<tr><td valign='bottom' ".$reportManager->getReportDataStyle()."></td>".$chartResult2."</tr>
                      </table>
                      </td>
                      </tr>
                      <tr><td colspan='2' ".$reportManager->getReportDataStyle()." align='center'><b>Subject Name</b></td></tr>
                     </table>";

    return $chartResult;   
}    
    
?>

