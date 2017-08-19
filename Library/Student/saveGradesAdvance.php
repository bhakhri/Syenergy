<?php
//This file saves student grades
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

    $gradingLabelName = trim($REQUEST_DATA['gradingLabelName']);
    
    $labelId = $REQUEST_DATA['labelId'];
    $subjectId = $REQUEST_DATA['subjectId'];
    $degreeId = $REQUEST_DATA['degreeId'];
    $gadeLabelId = $REQUEST_DATA['gadeLabelId'];
    $degreeList = $degreeId;
    $gradingFormula = $REQUEST_DATA['gradingFormula'];
    
    $gradingFrom = $REQUEST_DATA['ttGradeFrom'];
    $gradingTo = $REQUEST_DATA['ttGradeTo'];

    $gradingLabelName = trim($REQUEST_DATA['gradingLabelName1']);

    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    if (empty($gradingLabelName)) {
       echo ENTER_GRADING_LABEL_NAME;
       die;
    }

   // Findout Internal Total Marks
   $subjectArray = $studentManager->getSingleField('subject_to_class', 
                                                   'DISTINCT IFNULL(internalTotalMarks,0)+IFNULL(externalTotalMarks,0) AS internalTotalMarks',
                                                    "WHERE subjectId = '$subjectId' AND   classId = '$degreeId'");
   if(is_array($subjectArray) && count($subjectArray)>0 ) {
      $internalTotalMarks = $subjectArray[0]['internalTotalMarks'];
   }

    $cntArray = $studentManager->checkGradingLabelName($gradingLabelName);
    $cnt = $cntArray[0]['cnt'];
    if ($cnt > 0) {
	    echo GRADING_LABEL_NAME_ALREADY_EXISTS;
	    die;
    }

    $gradeRangesArray = $studentManager->getActiveSetGrades();
    
    $i=0;
    foreach($gradeRangesArray as $gradeRange) {
        $gradeId = $gradeRange['gradeId'];
        $gradeLabel = $gradeRange['gradeLabel'];
        $gradePoints = $gradeRange['gradePoints'];

         $gradingRangeFromNew = $gradingFrom[$i];
         $gradingRangeToNew = $gradingTo[$i];
        if (!is_numeric($gradingRangeFromNew) or !is_numeric($gradingRangeToNew)) {
            echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
            die;
        }
        if ($gradingRangeToNew < $gradingRangeFromNew) {
            echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
            die;
        }
    
        if($i!=0) {
            if ($gradingRangeFromNew >= $gradingRangeFrom and $gradingRangeFromNew <= $gradingRangeTo) {
              echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
              die;
            }
            if ($gradingRangeToNew >= $gradingRangeFrom and $gradingRangeToNew <= $gradingRangeTo) {
               echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
               die;
            }
        }
    
        $gradingRangeFrom = $gradingRangeFromNew;
        $gradingRangeTo = $gradingRangeToNew;
        $i++;
    }
    

    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        
	        $return = $studentManager->addGradingLabelInTransaction($gradingLabelName);
	        if ($return === false) {
		        echo ERROR_WHILE_CREATING_GRADING_LABEL;
		        die;
	        }
	        $gadeLabelId = SystemDatabaseManager::getInstance()->lastInsertId();
            
            $i=0;          
	        $insertStr = '';
	        $gradeSetId = '';
            $insertStrLast='';
	        foreach($gradeRangesArray as $gradeRange) {
		        $gradeId = $gradeRange['gradeId'];
		        $gradingRangeFromNew = $gradingFrom[$i];
                $gradingRangeToNew = $gradingTo[$i];
		        $gradeSetId = $gradeRange['gradeSetId'];
                if(!empty($insertStr)) {
			       $insertStr .= ',';
                   $insertStrLast .= ',';
		        }
		        $insertStr .= "($gadeLabelId, $gradingRangeFromNew, $gradingRangeToNew,$gradeId, $instituteId)";
                $insertStrLast .= "($gradingRangeFromNew, $gradingRangeToNew, $instituteId,$internalTotalMarks)";
                
                $studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFromNew, $gradingRangeToNew, $degreeList,$gradingFormula);
                $str = '';
                foreach($studentsArray as $studentRecord) {
                    $studentId = $studentRecord['studentId'];
                    $classId = $studentRecord['classId'];
                    if (!empty($str)) {
                        $str .= ',';
                    }
                    $str .= "'$studentId#$classId#$subjectId'";
                }
                if ($str != '') {
                    $whereCondition = " CONCAT(studentId,'#',classId,'#',subjectId) in ($str)";
                    $return = $studentManager->updateRecordInTransaction(TOTAL_TRANSFERRED_MARKS_TABLE, "gradingLabelId = $gadeLabelId, gradeId = $gradeId, gradeSetId = $gradeSetId", $whereCondition);
                    if ($return === false) {
                        echo FAILURE;
                        die;
                    }
                }
                $i++;  
	        }

            if($insertStr!='') {
    	        $return = $studentManager->addGradingScalesInTransaction($insertStr);
                if ($return === false) {
	   	           echo ERROR_WHILE_CREATING_GRADING_SCALES;
		           die;
	            }
                 
                // START Grading Scale Save
                $return = $studentManager->deleteLastGradingScalesInTransaction($internalTotalMarks);
                if($return === false) {
                   echo ERROR_WHILE_CREATING_GRADING_SCALES;
                   die;
                }
                  
                $return = $studentManager->addLastGradingScalesInTransaction($insertStrLast);
                if($return === false) {
                   echo ERROR_WHILE_CREATING_GRADING_SCALES;
                   die;
                }
            }
     	    
    
            ########################### CODE FOR AUDIT TRAIL STARTS HERE ##########################################    
            $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId IN ($degreeList)");
		    $className = UtilityManager::makeCSList($classNameArray, 'className');
            $courseNameArray = $studentManager->getSingleField('subject','subjectName',"WHERE subjectId = $subjectId");
		    $courseName = $courseNameArray[0]['subjectName'];
	    
	    
		    $auditTrialDescription = "grades have been saved for class: $className  courses: $courseName";
		    $type = STUDENTS_ARE_PROMOTED; //STUDENTS PROMOTED
		    //$auditTrialDescription .= $subjectList;
		    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
		    $commonQueryManager = CommonQueryManager::getInstance();
		    $returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
		    if($returnStatus == false) {
			  echo  "Error while saving data for audit trail";
			  die;
		    }
	        ########################### CODE FOR AUDIT TRAIL ENDS HERE ##########################################
            
	        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		        echo SUCCESS;
	        }
	        else {
		        echo FAILURE;
		        die;
	        }
    }
    else {
	    echo FAILURE;
	    die;
    }

?>
