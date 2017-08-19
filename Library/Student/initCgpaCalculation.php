<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
// Author :Parveen Sharma
// Created on : 16-Feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once($FE . "/Library/messages.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CgpaCalculation');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
 
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    global $sessionHandler;
    $queryDescription =''; 

    
    $degreeId = $REQUEST_DATA['class1'];
    $labelId = $REQUEST_DATA['labelId']; 
    $includeFail = $REQUEST_DATA['includeFail']; 
    
    $gradeIDeclareResult = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_ALLOW'); 
    if($gradeIDeclareResult=='') {
      $gradeIDeclareResult='0';  
    }

	
    if($degreeId=='') {
      $degreeId='0';  
    }
    
    if($includeFail=='') {
      $includeFail='1';  
    }
    
   
    $classSubjectsArray = $studentManager->getAllSubjectList($degreeId);
	$classSubjectList = UtilityManager::makeCSList($classSubjectsArray,'subjectId');
        
    
    $gradeNotEnteredSubjectsArray = $studentManager->getGradeNotEnteredSubjects($degreeId, $classSubjectList);
	$subjectCodeList = UtilityManager::makeCSList($gradeNotEnteredSubjectsArray,'subjectCode');
	if ($subjectCodeList != '') {
		echo GRADES_NOT_APPLIED_FOR_."\n".$subjectCodeList;
		die;
	}
    
    $creditsNotEnteredArray = $studentManager->getCreditsNotEnteredSubjects($degreeId, $classSubjectList);
	$subjectCodeList = UtilityManager::makeCSList($creditsNotEnteredArray,'subjectCode');
	if ($subjectCodeList != '') {
		echo CREDITS_NOT_ENTERED_FOR_."\n".$subjectCodeList;
		die;
	}
    
    $cgpaStuentList = $studentManager->doCGPANotExternalMarks($degreeId, $classSubjectList);   
    $studentListArray = $studentManager->doCGPAStudentList($degreeId, $classSubjectList);   
   
    if($gradeIDeclareResult=='0') { 
      $studentExternalArray = $studentManager->getStudentExternalMarks($degreeId); 
    }

    // ==================== Previous  Class (START) =========================
        // Student all previous class and subjects fetch
        $studyPeriodId = '';
        $previousClassArray = $studentManager->getStudentPreviousClasses($degreeId);
        if(count($previousClassArray) >0 ) {
          $studyPeriodId = UtilityManager::makeCSList($previousClassArray,'studyPeriodId');
        }
        if(trim($studyPeriodId)=='') {
          $studyPeriodId='0';  
        }
      
         
                                       
        // Student Previous Less Grades
        $failGradeCondition = " AND IFNULL(gr.failGrade,'') LIKE 'Y' ";  
        $conditionPrevious  = " AND c.studyPeriodId IN (".$studyPeriodId.") AND a.subjectId IN ($classSubjectList) $failGradeCondition "; 
        $conditionPrevious .= " AND ( (a.studentId,a.subjectId) IN 
                                      (SELECT 
                                           ttt.studentId,ttt.subjectId 
                                       FROM 
                                           ".TOTAL_TRANSFERRED_MARKS_TABLE." ttt 
                                       WHERE 
                                           ttt.studentId = a.studentId AND 
                                           ttt.subjectId = a.subjectId AND
                                           ttt.classId='$degreeId') ) ";
        $previousClassArray = $studentManager->getStudentPreviousGrade($conditionPrevious);   

        $preivousLessCheckArray = array();
        $preivousLessArray = array();
        for($i=0;$i<count($previousClassArray);$i++) {
            $studentId=$previousClassArray[$i]['studentId'];   
            $subjectId=$previousClassArray[$i]['subjectId'];
               
           
            $previousCredit=$previousClassArray[$i]['credits'];
            $previousGradePoint=$previousClassArray[$i]['totalGrade'];
            $lessCreditEarned=$previousClassArray[$i]['credits'];
            
            if(count($preivousLessCheckArray[$studentId][$subjectId])==0) {
               $preivousLessCheckArray[$studentId][$subjectId]='1';  
               $preivousLessArray[$studentId]['credits'] += $previousCredit; 
               $preivousLessArray[$studentId]['gradePoints'] += $previousGradePoint;
               $preivousLessArray[$studentId]['lessCreditEarned'] += $lessCreditEarned;
            }
            /*
             else {
                $preivousLessArray[$studentId]['credits'] += $previousCredit; 
                $preivousLessArray[$studentId]['gradePoints'] += $previousGradePoint;
                $preivousLessArray[$studentId]['lessCreditEarned'] += $lessCreditEarned;
             } 
            */
        }      
        
         
        
        
        // Student Previous Grades
        $preivousFinalArray = array();   
        for($i=0; $i<count($studentListArray); $i++) {
           $studentId =  $studentListArray[$i]['studentId'];
           
           $prevCredit='0';                               
           $prevGradePoint = '0';
           $prevEarnedPoint = '0';        
           
           $condition=" AND sg.studentId = '".$studentId."' AND c.studyPeriodId IN (".$studyPeriodId.") "; 
           $resourceRecordArray1 = $studentManager->getStudentClasswiseCGPA($condition);
           for($kk=0;$kk<count($resourceRecordArray1);$kk++) {
             $prevCredit = $prevCredit + $resourceRecordArray1[$kk]['netCredit']; 
             $prevGradePoint= $prevGradePoint + $resourceRecordArray1[$kk]['netGradePoint'];  
             $prevEarnedPoint = $prevEarnedPoint + $resourceRecordArray1[$kk]['netCreditEarned'];  
           }
           $preivousFinalArray[$studentId]['credits'] = $prevCredit; 
           $preivousFinalArray[$studentId]['gradePoints'] = $prevGradePoint;
           $preivousFinalArray[$studentId]['lessCreditEarned'] = $prevEarnedPoint;
        }
        
        
        
        // Student Current Grades
        $currentFinalArray = array();   
        for($i=0; $i<count($studentListArray); $i++) {
           $studentId =  $studentListArray[$i]['studentId'];
           
           $prevCredit='0';                               
           $prevGradePoint = '0';
           $prevEarnedPoint = '0';        
           
           
           $failGradeCondition = "";
           if($includeFail == '0') {
           //  $failGradeCondition = "  AND gr.failGrade <> 'Y' " ;
           }
           $condition=" AND a.studentId = '".$studentId."' AND c.classId = '".$degreeId."' $failGradeCondition  "; 
           $resourceRecordArray1 = $studentManager->getStudentPreviousGrade($condition);
           for($kk=0;$kk<count($resourceRecordArray1);$kk++) {
             $prevSubjectArray[] = array_merge($resourceRecordArray1[$kk]); 
             //if($resourceRecordArray1[$kk]['gradePoints']>0) { 
             if($gradeIDeclareResult=='0') { // No    
               if($resourceRecordArray1[$kk]['gradeId']!='') {   // grade != NULL 
                 $findIGrade='';
                 for($ee=0;$ee<count($studentExternalArray);$ee++) {
                    if($studentExternalArray[$ee]['studentId']==$resourceRecordArray1[$kk]['studentId'] && $studentExternalArray[$ee]['subjectId']==$resourceRecordArray1[$kk]['subjectId'] ) {
                      $findIGrade ='1';
                      break;  
                    } 
                 }
                 if($findIGrade=='') {
                   $prevCredit += $resourceRecordArray1[$kk]['credits'];    
                   $prevGradePoint += ($resourceRecordArray1[$kk]['credits']*$resourceRecordArray1[$kk]['gradePoints']);    
                 }
               }
             }
             else {
                $prevCredit += $resourceRecordArray1[$kk]['credits'];    
                $prevGradePoint += ($resourceRecordArray1[$kk]['credits']*$resourceRecordArray1[$kk]['gradePoints']);  
             }
             //}
           }
           
           // This value coming from Confing Parameter Base 
           // First Case
           // Fail Grade Condition(config parameter) => Yes 
           // To cgpa is calculated all grades.
           
           // Second Case
           // Fail Grade Condition(config parameter) => No 
           // To cgpa is calculated only for pass grades not include fail grade.
           $failGradeCondition = "";
           if($includeFail == '0') {
            // $failGradeCondition = "  AND gr.failGrade <> 'Y' " ;
           }
           $condition=" AND a.studentId = '".$studentId."' AND c.classId = '".$degreeId."' $failGradeCondition "; 
           $recordArray11 = $studentManager->getStudentPreviousGrade($condition);
           for($kk=0;$kk<count($recordArray11);$kk++) {
             //if($recordArray11[$kk]['gradePoints']>0) { 
             if($recordArray11[$kk]['failGrade']!='Y') {
               $prevEarnedPoint += $recordArray11[$kk]['credits'];
             }
             //}
           }
           $currentFinalArray[$studentId]['credits'] = $prevCredit; 
           $currentFinalArray[$studentId]['gradePoints'] = $prevGradePoint;
           $currentFinalArray[$studentId]['lessCreditEarned'] = $prevEarnedPoint;
        }
        
    // ==================== Previous  Class (END) =========================
    
    
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $returnStatus = $studentManager->deleteClassCGPA($degreeId);
		if ($returnStatus == false) {
			echo FAILURE;
			die;
		}
        for($i=0; $i<count($studentListArray); $i++) {
           $studentId =  $studentListArray[$i]['studentId'];
        
           $subjectNewList= '';
           for($k=0; $k<count($classSubjectsArray); $k++) {
              $subjectId = $classSubjectsArray[$k]['subjectId'];
             
              $find='';
              for($j=0; $j<count($cgpaStuentList); $j++) {
                 if($cgpaStuentList[$j]['studentId'] == $studentId  && $cgpaStuentList[$j]['subjectId'] == $subjectId) {
                    $find='1';   
                    break;     
                 }
              }
              if($find=='') {
                if($subjectNewList=='') {
                  $subjectNewList = $subjectId;    
                }
                else {    
                  $subjectNewList .=",".$subjectId;  
                }
              }
           }
                                                                                    
           $netGradePoint='';
           $netCredit='';
           if($subjectNewList!='') {
               //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
               $failGradeCondition = "";
               if($includeFail == '0') {
                 $failGradeCondition = "  AND c.failGrade <> 'Y' " ;
               }
               $returnStatus = $studentManager->doCGPACalculationNew($degreeId, $subjectNewList,$studentId,$failGradeCondition,$includeFail);  
               if ($returnStatus == false) {
                  echo FAILURE;
                  die;
               }
               
               // Current
               if(count($currentFinalArray[$studentId])>0) { 
                 $lessCredit = $currentFinalArray[$studentId]['credits'];
                 $lessCreditEarned = $currentFinalArray[$studentId]['lessCreditEarned'];
                 $lessGradePoint = $currentFinalArray[$studentId]['gradePoints'];
               }
               if(trim($lessCredit)=='') {
                 $lessCredit='0';  
               }
               if(trim($lessCreditEarned)=='') {
                 $lessCreditEarned='0';  
               }
               if(trim($lessGradePoint)=='') {
                 $lessGradePoint='0';
               }
               $returnStatus = $studentManager->doCGPACurrentUpdate($degreeId, $studentId, $lessCredit, $lessCreditEarned, $lessGradePoint);    
               if ($returnStatus == false) {
                  echo FAILURE;
                  die;
               }
                   
               $netGradePoint = $lessGradePoint; 
               $netCredit = $lessCredit;
                
                   
               // Previous
               if(count($preivousFinalArray[$studentId])>0) { 
                 $lessCredit = $preivousFinalArray[$studentId]['credits'];
                 $lessCreditEarned = $preivousFinalArray[$studentId]['lessCreditEarned'];
                 $lessGradePoint = $preivousFinalArray[$studentId]['gradePoints'];
               }
               if(trim($lessCredit)=='') {
                 $lessCredit='0';  
               }
               if(trim($lessCreditEarned)=='') {
                 $lessCreditEarned='0';  
               }
               if(trim($lessGradePoint)=='') {
                 $lessGradePoint='0';
               }
               $returnStatus = $studentManager->doCGPAPreviousUpdate($degreeId, $studentId, $lessCredit, $lessCreditEarned, $lessGradePoint);    
               if ($returnStatus == false) {
                  echo FAILURE;
                  die;
               }
               
               $netGradePoint = $netGradePoint + $lessGradePoint; 
               $netCredit = $netCredit + $lessCredit;
               
             
               
               if(count($preivousLessCheckArray[$studentId])>0) {
                  $lessCredit = $preivousLessArray[$studentId]['credits'];
                  $lessCreditEarned = $preivousLessArray[$studentId]['lessCreditEarned'];
                  $lessGradePoint = $preivousLessArray[$studentId]['gradePoints'];
                  if(trim($lessCredit)=='') {
                     $lessCredit='0';  
                  }
                  if(trim($lessCreditEarned)=='') {
                     $lessCreditEarned='0';  
                  }
                  if(trim($lessGradePoint)=='') {
                     $lessGradePoint='0';
                  }
                  $returnStatus = $studentManager->doCGPALessUpdate($degreeId, $studentId, $lessCredit, $lessCreditEarned, $lessGradePoint);    
                  if ($returnStatus == false) {
                    echo FAILURE;
                    die;
                  }
                  
                  $netGradePoint = $netGradePoint - $lessGradePoint; 
                  $netCredit = $netCredit - $lessCredit;
               } 
               
               $returnStatus = $studentManager->doCGPANet($degreeId, $studentId);    
               if($returnStatus == false) {
                 echo FAILURE;
                 die;
               }
               
               // GPA
                if(count($currentFinalArray[$studentId])>0) { 
                 $lessCredit = $currentFinalArray[$studentId]['credits'];
                 $lessGradePoint = $currentFinalArray[$studentId]['gradePoints'];
               }
               if(trim($lessCredit)=='') {
                 $lessCredit='0';  
               }
               if(trim($lessGradePoint)=='') {
                 $lessGradePoint='0';
               }
               
               if($lessCredit=='0') {  
                 $gpa='0';  
               }
               else {
                 $gpa = $lessGradePoint/$lessCredit;  
               }
               if(trim($gpa)=='') {
                 $gpa='0';  
               }
               
               // CGPA
               if(trim($netCredit)=='') {
                 $netCredit='0';  
               }
               if(trim($netGradePoint)=='') {
                 $netGradePoint='0';
               }
               
               if($netCredit=='0') {  
                 $cgpa='0';  
               }
               else {
                 $cgpa = $netGradePoint/$netCredit;  
               }
               if(trim($cgpa)=='') {
                 $cgpa='0';  
               }
               $returnStatus = $studentManager->doGPACGPA($degreeId, $studentId, $gpa,$cgpa);    
               if ($returnStatus == false) {
                  echo FAILURE;
                  die;
               }
           }
        }
       
       //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
	   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		  ########################### CODE FOR AUDIT TRAIL STARTS HERE ##########################################
		  require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	   	  $commonQueryManager = CommonQueryManager::getInstance();
		  $result = $commonQueryManager->getClassName($degreeId);
		  $className = $result[0]['className'];
		  $type =CGPA_IS_CALCULATED;
		  $auditTrailDescription = "CGPA has been calculated for class: $className";
		  $returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		  if($returnStatus == false) {
			echo ERROR_WHILE_SAVING_DATA_IN_AUDIT_TRAIL;
			die;
		  }
		  ########################### CODE FOR AUDIT TRAIL ENDS HERE ##########################################
		  echo SUCCESS;
		}
		else {
		  echo FAILURE;
		}
	}
	else {
		echo FAILURE;
	}
?>
