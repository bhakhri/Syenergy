<?php
/*
  This File calls addFunction used in adding Grade Records
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/GradeManager.inc.php");
        
	$gradeLabel=$REQUEST_DATA['gradeLabel'];
	$gradePoints=$REQUEST_DATA['gradePoints'];
	$hiddenGradeIdBox=$REQUEST_DATA['hiddenGradeIdBox'];
	$gradeSetId=$REQUEST_DATA['gradeSetId'];
    $failGrade=$REQUEST_DATA['failGrade'];  
    $gradeStatus=$REQUEST_DATA['gradeStatus'];  


	$filter='';
	if(SystemDatabaseManager::getInstance()->startTransaction()) {   
		for($i=0;$i<count($hiddenGradeIdBox);$i++){
		    $condition='';
		    if($hiddenGradeIdBox[$i]=='-1'){
		      $filter='INSERT';
		    }
		    else{
		      $filter='UPDATE';
		      $condition="WHERE gradeId=$hiddenGradeIdBox[$i]";
		    }
		    $returnStatus = GradeManager::getInstance()->updateGradeDescription($filter,$gradeSetId,$gradeLabel[$i],$gradePoints[$i],$condition,$failGrade[$i],$gradeStatus[$i]);
		    if($returnStatus === false) {
		   	echo FAILURE;
			die;
		    }
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	   	     echo SUCCESS;
	  	     die;
	  	}
	  	else {
	   	     echo FAILURE;
	  	     die;
	  	}
	}

?>


