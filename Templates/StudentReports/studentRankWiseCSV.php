<?php 
// This file generate a list Student Rank wise Report CSV
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRank');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
    
                                                            
     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments; 
         }
    }
    
    
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
      $rankVal = "Rank,All";   
    }
    
    // Rank  1 = Above,  2 = below, 3 = Equal
    if($REQUEST_DATA['rank']==1) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) > ".$REQUEST_DATA['rankValue'];
        $rankVal = "Rank Above,".parseCSVComments(trim($REQUEST_DATA['rankValue']));
    }
    else if($REQUEST_DATA['rank']==2) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) < ".$REQUEST_DATA['rankValue'];
        $rankVal = "Rank Below,".parseCSVComments(trim($REQUEST_DATA['rankValue']));
    }
    else if($REQUEST_DATA['rank']==3) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) = ".$REQUEST_DATA['rankValue'];
        $rankVal = "Rank Equal,".parseCSVComments(trim($REQUEST_DATA['rankValue']));
    }
    
    
    // Student Academic Details
    $condition1 =" AND sg.classId IN ($classId)";
    $academicArray = $studentManager->getStudentAcademic($condition1);
    $academicCount = count($academicArray);
  
    $conditions .=" AND s.classId IN ($classId) ";
    
    
    //  ========== Search Start =============
        $csvData = "";
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
        
        $csvData .= "Time Table,".parseCSVComments($timeTableName)."\n";
        $csvData .= "Class,".parseCSVComments($className2)."\n";
        $csvData .= "Exam By,".parseCSVComments($examClassVal).",".$rankVal."\n";
        $csvData .= "As On,".parseCSVComments($formattedDate)."\n";  
    //  ========== Search End ============= 
    
    


    $recordArray = $studentManager->getStudentRankWise($conditions, $orderBy);
    $cnt = count($recordArray);
    

    $csvData .= "Sr. No., Student Name, URoll. No., CRoll No., Exam. By, Exam. RNo.,Rank ";    
    foreach($classResults as $key=>$value)  {
       $examClassVal = $value;  
       $csvData .= ",".parseCSVComments($examClassVal).",%age";
    } 
    $csvData .= "\n";
    
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

       $csvData .= ($i+1).",".parseCSVComments($recordArray[$i]['studentName']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['universityRollNo']).",".parseCSVComments($recordArray[$i]['rollNo']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['compExamBy']).",".parseCSVComments($recordArray[$i]['compExamRollNo']);
       $csvData .= ",".parseCSVComments($recordArray[$i]['compExamRank']); 
       
       for($j=0; $j<count($cntArr); $j++) {
          $str = $cntArr[$j];  
          $str1 = "m".substr($cntArr[$j],1,strlen($str));
          $recordArray[$i][$str] = NOT_APPLICABLE_STRING;
          $recordArray[$i][$str1] = NOT_APPLICABLE_STRING;
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
            $mksChk = NOT_APPLICABLE_STRING; 
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
       
       for($j=0; $j<count($cntArr); $j++) {
          $str = $cntArr[$j];  
          $str1 = "m".substr($cntArr[$j],1,strlen($str)); 
          $csvData .= ",".parseCSVComments($recordArray[$i][$str1]).",".parseCSVComments($recordArray[$i][$str]);  
       }
       $csvData .= "\n";
    }    
    
    
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a PDF
    header('Content-type: application/octet-stream');
    header("Content-Length: " .strlen($csvData) );
    // It will be called downloaded.pdf
    header('Content-Disposition: attachment;  filename="StudentRankwiseList.csv"');
    // The PDF source is in original.pdf
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;     

// $History: studentRankWiseCSV.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/08/10    Time: 2:42p
//Updated in $/LeapCC/Templates/StudentReports
//time table label base report format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/11/10    Time: 3:42p
//Updated in $/LeapCC/Templates/StudentReports
//sorting order format updated
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/09/10    Time: 6:05p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug: 2828
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/24/09   Time: 2:49p
//Updated in $/LeapCC/Templates/StudentReports
//sorting order updated
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/23/09   Time: 6:49p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos. 0002099, 0002105, 0002096, 0002080
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/12/09    Time: 2:14p
//Created in $/LeapCC/Templates/StudentReports
//file added
//

?>