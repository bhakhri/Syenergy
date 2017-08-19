<?php
// This file generate a list Student Rank wise Report Print
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php                
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRank');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance(); 
    

    global $classResults;   
    
    $conditions = "";
    $timeTableLabelId = trim($REQUEST_DATA['timeTable']);
    $classResultId = trim($REQUEST_DATA['classResultId']);
    $classId = trim($REQUEST_DATA['classId']);
    $rankValue = trim($REQUEST_DATA['rankValue']);
    $rank = trim($REQUEST_DATA['rank']);
    $examId = trim($REQUEST_DATA['examId']); 

    
    // Search filter
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else 
    if ($sortField == 'regNo') {
        $sortField1 = 'IF(IFNULL(regNo,"")="" OR regNo = "'.NOT_APPLICABLE_STRING.'",s.studentId, regNo)';
    }
    else
    if ($sortField == 'className') {
       $sortField1 = "s.studentId $sortOrderBy, s.classId";   
    }
    else
    if ($sortField == 'compExamBy') {
        $sortField1 = 'IF(IFNULL(compExamBy,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, compExamBy)';
    }
    else
    if ($sortField == 'compExamRollNo') {
        $sortField1 = 'IF(IFNULL(compExamRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRollNo AS UNSIGNED) )';
    }
    else
    if ($sortField == 'compExamRank') {
        $sortField1 = 'IF(IFNULL(compExamRank,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRank AS UNSIGNED))';
    }
    else {
        $sortField = 'studentName';
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';   
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    
    
    if($classResultId != ''){
      $cntArr = explode(',',$classResultId);
    }
    
    if($classId == ''){
      $classId = 0;      
    }
    
    if($REQUEST_DATA['examId']!='all') {
      $conditions .=" AND compExamBy = ".$REQUEST_DATA['examId'];  
    }
    
    if($REQUEST_DATA['rank']=='') {
      $rankVal = "<b>Rank:</b> All";   
    }
    
    // Rank  1 = Above,  2 = below, 3 = Equal
    if($REQUEST_DATA['rank']==1) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) > ".$REQUEST_DATA['rankValue'];
        $rankVal = "<b>Rank Above:</b> ".$REQUEST_DATA['rankValue'];
    }
    else if($REQUEST_DATA['rank']==2) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) < ".$REQUEST_DATA['rankValue'];
        $rankVal = "<b>Rank Below:</b> ".$REQUEST_DATA['rankValue'];
    }
    else if($REQUEST_DATA['rank']==3) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) = ".$REQUEST_DATA['rankValue'];
        $rankVal = "<b>Rank Equal:</b> ".$REQUEST_DATA['rankValue'];
    }
    
    
    // Student Academic Details
    $condition1 =" AND sg.classId IN ($classId)";
    $academicArray = $studentManager->getStudentAcademic($condition1);
    $academicCount = count($academicArray);
  
    $conditions .=" AND s.classId IN ($classId) ";
    
    
    //  ========== Search Start =============
        $reportHead = "";
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);    
            
        $timeNameArray = $studentManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
        $timeTableName = $timeNameArray[0]['labelName'];
        if($timeTableName=='') {
          $timeTableName = NOT_APPLICABLE_STRING;  
        }

        $classNameArray = $studentManager->getSingleField('class', 'className AS className', "WHERE classId  = $classId");
        $className = $classNameArray[0]['className'];
        $className2 = str_replace("-",' ',$className);
        if($className2=='') {
          $className2 = NOT_APPLICABLE_STRING;  
        }
        
        if($REQUEST_DATA['examId']!='all') {
          $examClassVal = $classResults[examId];  
        }
        else {
          $examClassVal = 'All';  
        }
        
        $reportHead = "<B>Time Table:</b>&nbsp;$timeTableName<br>
                       <B>Class:</b>&nbsp;$className2<br>
                       <B>Exam By:</B>&nbsp;$examClassVal&nbsp;,
                       $rankVal<br>
                        As On $formattedDate ";
        
    //  ========== Search End ============= 
    
    $recordArray = $studentManager->getStudentRankWise($conditions, $orderBy);
    $cnt = count($recordArray);
    
    for($i=0;$i<$cnt;$i++) {   
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $studentId = $recordArray[$i]['studentId'];
       
       if($recordArray[$i]['compExamBy'] == '' || $recordArray[$i]['compExamBy'] == 'NULL' || $recordArray[$i]['compExamBy'] == 'NA') {
          $recordArray[$i]['compExamBy']=NOT_APPLICABLE_STRING; 
       }
       else {
          $recordArray[$i]['compExamBy']=$results[$recordArray[$i]['compExamBy']];
       }
       
       if($recordArray[$i]['compExamRollNo']=='') {
         $recordArray[$i]['compExamRollNo']=NOT_APPLICABLE_STRING;
       }
       
       if($recordArray[$i]['compExamRank']=='') {
         $recordArray[$i]['compExamRank']=NOT_APPLICABLE_STRING;
       }

       for($j=0; $j<count($cntArr); $j++) {
          $str = $cntArr[$j];  
          $str1 = "m".substr($cntArr[$j],1,strlen($str));
          $recordArray[$i][$str] = NOT_APPLICABLE_STRING."&nbsp;&nbsp;&nbsp;";
          $recordArray[$i][$str1] = NOT_APPLICABLE_STRING."&nbsp;&nbsp;&nbsp;";
       }

       $find=0;       
       for($k=0; $k<$academicCount; $k++) {
          $aStudentId = $academicArray[$k]['studentId'];
          $per = $academicArray[$k]['previousPercentage'];  
          $mks = number_format($academicArray[$k]['previousMarks'],0);
          $maxMarks = number_format($academicArray[$k]['previousMaxMarks'],0);
          $examClass = "e".$academicArray[$k]['previousClassId'];
          $examClassM = "m".$academicArray[$k]['previousClassId'];  
          $mksChk = "$mks/$maxMarks";
          if($mksChk=='') {
            $mksChk = NOT_APPLICABLE_STRING."&nbsp;&nbsp;&nbsp;"; 
          }
          if($aStudentId == $studentId) {
            $find=1;
            for($j=0; $j<count($cntArr); $j++) {
               $str = $cntArr[$j];  
               if($str==$examClass) { 
                 $recordArray[$i][$examClassM] = $mksChk;     
                 $recordArray[$i][$str] = $per;
                 break;
               }
            }
          }  
          else if($find==1) {
            break;  
          }
       }
       
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);   
    }
    
    $reportTableHead = array();          
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Exam Rankwise Report');
    $reportManager->setReportInformation($reportHead);    
   
                //associated key                        col.label,      col. width,      data align
    $reportTableHead['srNo']                = array('#',                'width="2%"  align="left"', 'align="left"');
    $reportTableHead['studentName']         = array('Student Name',     'width="18%" align="left"', 'align="left"');
    //$reportTableHead['className']         = array('Class',            'width="15%" align="left"',  'align="left"');
    $reportTableHead['universityRollNo']    = array('URoll. No.',       'width="11%" align="left"', 'align="left"');
    $reportTableHead['rollNo']              = array('CRoll No.',        'width="10%" align="left"', 'align="left"');
    $reportTableHead['compExamBy']          = array('Exam. By',         'width="9%" align="left"', 'align="left"');
    $reportTableHead['compExamRollNo']      = array('Exam. RNo.',       'width="9%" align="left"', 'align="left"');
    $reportTableHead['compExamRank']        = array('Rank',             'width="9%" align="left"', 'align="left"');
 
    foreach($classResults as $key=>$value)  {
       $examClassIdM = "m".$key;  
       $examClassId= "e".$key; 
       $examClassVal = $value;  
       $reportTableHead[$examClassIdM]      = array($examClassVal,     'width="8%" align="right"', 'align="right"');  
       $reportTableHead[$examClassId]       = array('%age',     'width="8%" align="right"', 'align="right"');
    } 
    
    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
    
// $History: studentRankWisePrint.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/08/10    Time: 2:42p
//Updated in $/LeapCC/Templates/StudentReports
//time table label base report format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 2/15/10    Time: 11:17a
//Updated in $/LeapCC/Templates/StudentReports
//report heading name updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/11/10    Time: 3:42p
//Updated in $/LeapCC/Templates/StudentReports
//sorting order format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/11/10    Time: 2:41p
//Updated in $/LeapCC/Templates/StudentReports
//tag name updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/24/09   Time: 3:24p
//Updated in $/LeapCC/Templates/StudentReports
//reportwidth  updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/24/09   Time: 2:49p
//Updated in $/LeapCC/Templates/StudentReports
//sorting order updated
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/23/09   Time: 6:49p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos. 0002099, 0002105, 0002096, 0002080
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/03/09    Time: 11:08a
//Updated in $/LeapCC/Templates/StudentReports
//Gurkeerat: resolved issue 1425
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/12/09    Time: 2:14p
//Created in $/LeapCC/Templates/StudentReports
//file added
//


?>
