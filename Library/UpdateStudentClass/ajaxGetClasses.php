<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");   
    $studentManager = StudentManager::getInstance();
    
	 
	$chkClasses = $REQUEST_DATA['chkClasses'];
    
    $rollNo    = $REQUEST_DATA['rollNo'];
    $criteria    = $REQUEST_DATA['criteria'];
    
    if($criteria == 1) {
      $conditions = "WHERE rollNo='".$rollNo."'";
    }
    else if ($criteria == 2) {
      $conditions = "WHERE universityRollNo='".$rollNo."'";
    }
    else if ($criteria == 3) {
      $conditions = "WHERE regNo='".$rollNo."'";
    }
    
    // Findout Class Name
   $classNameArray = $studentManager->getSingleField('student', 'classId', "$conditions");
   $classId = $classNameArray[0]['classId'];  
   if($classId=='') {
     $classId=0;  
   }

    if($chkClasses=='') {
      $chkClasses=1;  
    }
    
    if($chkClasses == '1') {
      $classArray = $commonQueryManager->geClass();
    }
    else {
      $conditions = "WHERE classId = $classId";
      $subjectArray = $studentManager->getSingleField('subject_to_class', 'subjectId', "$conditions");
     
      $subjectList="0";
      for($i=0;$i<count($subjectArray);$i++) {
        $subjectList .=",".$subjectArray[$i]['subjectId'];
      }
      
      $conditions = " AND sub.subjectId IN ($subjectList)";    
      $classArray = $commonQueryManager->getClassList($conditions); 
    }
    
    
    if(is_array($classArray) && count($classArray)>0 ) {  
        echo json_encode($classArray).'!~!~!'.$classId;
    }
    else {
        echo 0;                 
    }
	
    

// $History: initClassGetGroups.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 11/11/09   Time: 6:07p
//Created in $/LeapCC/Library/UpdatePassword
//added file to fetch group on basis of class in LeapCC
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:55p
//Updated in $/Leap/Source/Library/StudentReports
//applied code to make it working in IE
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/14/08    Time: 4:05p
//Created in $/Leap/Source/Library/StudentReports
//file added for test wise marks report
//

?>