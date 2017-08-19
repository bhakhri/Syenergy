<?php 


//  This File calls addFunction used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/GetSubjectManager.inc.php");
	$subjectManager =SubjectManager::getInstance();

	global $sessionHandler;


	$studentId=$REQUEST_DATA['studentId'];
	$SubjectNameString=explode(",",$REQUEST_DATA['SubjectNameString']);
	$MarksObtainedValueString=explode(",",$REQUEST_DATA['MarksObtainedValueString']);
	$MaxMarksValueString=explode(",",$REQUEST_DATA['MaxMarksValueString']);
        $previousClassId = $REQUEST_DATA['previousClassId'];
	$item = $REQUEST_DATA['rowCountTotal'];	

	// Check MarksObtained and MaxMarks
        for($j=0; $j<$item; $j++) {
          $marksObtained = trim($MarksObtainedValueString[$j]);
          $maxMarks = trim($MaxMarksValueString[$j]);
          if($marksObtained !='' && $maxMarks != '') {
 	    if($marksObtained>$maxMarks) {
               echo "MARKS OBTAINED CANNOT BE GREATER THAN MAX. MARKS";
	       die;
            }
	  }
	} 
	
	if(count($item)>0) {				
	  for($j=0; $j<$item; $j++) {
	     if(!empty($str)) {
	       $str .= ',';
	     }
	     $str .= "('$studentId','$SubjectNameString[$j]', '$MarksObtainedValueString[$j]', '$MaxMarksValueString[$j]','$previousClassId')";
	   }
		   if(SystemDatabaseManager::getInstance()->startTransaction()) {
              
	      $result = SubjectManager::getInstance()->deleteSubjectDetail($studentId,$previousClassId);
	      if($result === false) {
                 echo FALIURE;
                 die;
              }	
	      $result = SubjectManager::getInstance()->addSubjectDetail($str);
	      if(SystemDatabaseManager::getInstance()->commitTransaction()){
		if($result){
		  echo SUCCESS;
		}
		else{
		  echo FALIURE;
		}
	      }
	   }
	} 
?>
