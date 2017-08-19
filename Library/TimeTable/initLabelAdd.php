<?php
//-------------------------------------------------------
// Purpose: to design the layout for add label to class
//
// Author : Rajeev Aggarwal
// Created on : (30.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssociateTimeTableToClass');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timetableManager  = TimeTableManager::getInstance();

//$errorMessage ='';
global $sessionHandler;
$sessionId = $sessionHandler->getSessionVariable('SessionId');
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
//$saveClassId = $REQUEST_DATA['saveClassId']; 
//$cancelClassId = $REQUEST_DATA['cancelClassId']; 
$labelId = $REQUEST_DATA['labelId']; 
$errorMessage = '';
if($labelId=='') {
   echo SELECT_TIMETABLE;
   die;
}

# CHECKING MISSING CLASS FOR WHICH TIME TABLE IS CREATED, BUT NOT SELECTED BY USER
$classNameTimeTable= array();
$classNameArray=array();
$classIdArray = $REQUEST_DATA['chb'];
$missingClassIdArray = array();
if ($classIdArray[0] != '') {
	$timeTableClassIdArray = $timetableManager->getTimeTableClasses($labelId);
	if ($timeTableClassIdArray[0]['classId'] != '') {
		foreach ($timeTableClassIdArray as $record) {
			$classId = $record['classId'];
			if (!in_array($classId, $classIdArray)) {
				$missingClassIdArray[] = $classId;
			}			
		}
		$missingClassIdList = implode(',', $missingClassIdArray);
		if($missingClassIdList !='') {			
		   $classNameArray = $timetableManager->getClassName($missingClassIdList);
		   $classNames = UtilityManager::makeCSList($classNameArray,'className',"\n");
		   echo MISSED_CLASS_TIMETABLE_CREATED."\n".$classNames;
		   die;
		}
	}
}
else {
	$countLabelClassesArray = $timetableManager->countTimeTableClasses($labelId);
	$cnt = $countLabelClassesArray[0]['cnt'];
	if ($cnt > 0) {
		echo ClASSES_MAPPED_TO_TIMETABLE_FOR_LABEL;
		die;
	}
}
if (!is_array($classIdArray)) {
	$classIdArray = array();
}
# CHECKING IF THE SELECTED CLASS IS MAPPED WITH ANY OTHER TIME TABLE LABEL ALREADY
foreach ($classIdArray as $classId) {
	$countOtherTimeTableRecordArray = $timetableManager->checkWithOtherTimeTableLabels($labelId, $classId);
	$cnt = $countOtherTimeTableRecordArray[0]['cnt'];
	if ($cnt > 0) {
		echo ClASSES_MAPPED_OTHER_TIMETABLE_LABEL;
		die;
	}
}

# REACHED HERE, MEANS ALL THE DATA IS VALID, NOW DELETE MAPPED CLASSES, AND INSERT NEWLY SELECTED CLASSES
$condition ='';

if(SystemDatabaseManager::getInstance()->startTransaction()) {
	$condition = "timeTableLabelId = $labelId";
	$returnStatus =  $timetableManager->deleteAssignTimeTableClasses($condition);  
	if($returnStatus == false) {
		echo FAILURE; 
		die;
	}
	$str = '';
	foreach ($classIdArray as $classId) {
		if($str != '') {
			$str .= ",";
		}
		$str .= "($labelId,$classId)";
	}
	if($str!='') {
		$returnStatus = $timetableManager->addTimeTableClasses($str);
		if($returnStatus === false) {
			echo FAILURE; 
			die;
		}
	}
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		echo TIME_TABLE_CLASS_UPDATE_SUCCESSFULLY;
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



	 



      

/*
    $id='';   
    if($saveClassId!='') {
        $condition = " AND tc.classId IN ($saveClassId) AND tc.timeTableLabelId != $labelId";
        $foundArray = $timetableManager->getCheckTimeTableClasses($condition); 
	  
		  $cnt = count($foundArray);
		  for($i = 0; $i < $cnt; $i++) {     
           $classId = $foundArray[$i]['classId'];
		   if($id=='') {
             $id =$classId;
           }
           else { 
             $id .="~~".$classId;
			 
           }
        }
    }

      
    if($cancelClassId!='') {
        $condition = " AND tc.classId IN ($cancelClassId) AND tc.timeTableLabelId = $labelId AND tc.toDate IS NULL";
        $foundArray = $timetableManager->getCheckTimeTable($condition); 
        $cnt = $foundArray[0]['cnt'];
        for($i = 0; $i < $cnt; $i++) {     
           $classId = $foundArray[$i]['classId'];
           if($id=='') {
             $id =$classId;
           }
           else {
             $id .="~~".$classId;
           }
        }
    }
*/
/*
	$condition ='';
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              
              if($saveClassId!='') {
                  $classArray = explode(',',$saveClassId);     
                  if(count($classArray)>0) {
                     //`instituteId`,`sessionId`, `feeCycleId`,`classId` 
                     for($i = 0; $i < count($classArray); $i++) { 
                        $classId = $classArray[$i]; 
                        if($str=='') {
                          $str = "($labelId,$classId)";
                        } 
                        else {
                          $str .= ",($labelId,$classId)";  
                        }
                     }
                  }
              }
          
              $classId='';
              if($cancelClassId!='') {
                  $classArray = explode(',',$cancelClassId);     
                  if(count($classArray)>0) {  
                     $classId='-1'; 
                     for($i = 0; $i < count($classArray); $i++) { 
                        $classId .= ",".$classArray[$i];
                     }
                  }
              }
              
              if($str!='') {
                  $returnStatus = $timetableManager->addTimeTableClasses($str);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
                  $recordStatus=1;
              }
              
              if($classId!='') { 
                
                  $condition = " classId IN ($classId) AND timeTableLabelId = $labelId";
                  $returnStatus =  $timetableManager->deleteAssignTimeTableClasses($condition);  
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
                  $recordStatus=2;
              }
                
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 if($recordStatus==1) {
                   $errorMessage = TIME_TABLE_CLASS_ADDED_SUCCESSFULLY;  
                 }
                 else if($recordStatus==2) { 
                   $errorMessage = TIME_TABLE_CLASS_UPDATE_SUCCESSFULLY;
                 }
                 else if($recordStatus==3) {
                   $errorMessage = TIME_TABLE_CLASS_DELETE_SUCCESSFULLY;  
                 }
              }
              else {
                 $errorMessage = $id;
              }    
        }
    }
    echo $errorMessage;


 /*   if (trim($errorMessage) == '') {
		$labelId = $REQUEST_DATA['labelId'];
		$returnStatus   = $timetableManager->insertLabelToClass($labelId);
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	else {
        echo $errorMessage;
    }
*/
// $History: initLabelAdd.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:47p
//Updated in $/Leap/Source/Library/TimeTable
//applied role level access
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/30/08    Time: 6:13p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
?>