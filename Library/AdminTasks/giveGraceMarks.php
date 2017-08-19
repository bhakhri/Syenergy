<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
//
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
      require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    define('MODULE','GraceMarks');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");

	global $sessionHandler;
	$queryDescription =''; 
    $teacherManager = AdminTasksManager::getInstance();
    $commonQueryManager =  CommonQueryManager::getInstance(); 
	$studentIds=explode(',',$REQUEST_DATA['studentIds']);
    $graceMarks=explode(',',$REQUEST_DATA['graceMarks']);
	$classId = $REQUEST_DATA['classId'];
	$subjectId = $REQUEST_DATA['subjectId'];
    $cnt=count($studentIds);
	$classNameArray = $teacherManager->getClassName($classId);
	$className = $classNameArray[0]['className'];
	$subjectCodeArray = $teacherManager->getSubjectCode($subjectId);
	$subjectCode = $subjectCodeArray[0]['subjectCode'];
	if(trim($sessionHandler->getSessionVariable('GRACE_MARKS_ALLOWED'))=="0"){
               echo "You cannot provide grace marks. Please contact your administrator for details";
                die;  
            }
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
    for($i=0;$i<$cnt;$i++) {
        //create the condition
        $conditions ='';
        $conditions = " AND ttm.classId=".$classId." AND ttm.subjectId=".$subjectId." AND sg.groupId=".$REQUEST_DATA['group'].' AND s.studentId='.$studentIds[$i];
        
        if(trim($REQUEST_DATA['studentRollNo'])!=''){
          $conditions .=' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'"';
        }
        
        $graceMarksRecordArray = $teacherManager->getGraceMarksList($conditions);
        
        //IF (MARKS SCORED+GRACE MARKS > MAX MARKS)*****************
        if(($graceMarks[$i] + $graceMarksRecordArray[0]['marksScored']) > $graceMarksRecordArray[0]['maxMarks'] ){
            echo GRACE_MARKS_VALIDATION;
            die;
        }
        else{
            $ret1=$teacherManager->deleteGraceMarks($studentIds[$i],$classId,$subjectId);
	     $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
            if($ret1===true){
                $ret2=$teacherManager->insertGraceMarks($studentIds[$i],$classId,$subjectId,$graceMarks[$i]);
                if($ret2===false){
                    echo FAILURE;
                    die;
                }
		 $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
            }
            else{
                echo FAILURE;
                die;
            }
        }
    }
	
	
   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	 ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$auditTrialDescription = 'Grace Marks Entered for class :'.$className.' subject: "'.$subjectCode.'"';
		$type = GRACE_MARKS_ARE_ENTERED;
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
	   ########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
       echo SUCCESS;
       die;
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

    
// for VSS
// $History: giveGraceMarks.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/12/09    Time: 12:07
//Updated in $/LeapCC/Library/AdminTasks
//Corrected code for mba
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 4/06/09    Time: 10:47
//Created in $/LeapCC/Library/AdminTasks
//Created grace marks module in admin end
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 11:06
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected query and logic in grace marks modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Grace Marks Master"
?>
