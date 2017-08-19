<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of student marks
//
// Author : Nancy Puri
// Created on : (10.11.2010 )
//------------------------------------------------------------------------------------------------------
    /*
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();
    */
     require_once(MODEL_PATH . "/StudentReportsManager.inc.php");        
    $studentReportsManager = StudentReportsManager::getInstance();
          
	/* START: function to fetch student marks detail */
	$strList ="";
    $studentMarksArray = array();
    $studentMarksArray = $studentReportsManager->getStudentGraphArray(" AND stc.classId=$degree AND stu.studentId=$studentId");
	$cnt = count($studentMarksArray); 
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {
        
        $strList .= "<value xid='".$i."'>".$studentMarksArray[$i]['subjectCode']."</value>\n";   
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";

	for($k=1;$k<2;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {
             $subjectId =  $studentMarksArray[$i]['subjectId'];
            $strList .= "<value xid='".$i."' url='javascript:showData(".$studentMarksArray[$i]['subjectId'].")'>".$subjectPercentageArray[$i][$subjectId]."</value>\n"; 
		
        }
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentMarksBarGraph".$studentId.".xml";    
	UtilityManager::writeXML($strList, $xmlFilePath);
    
	/* END: function to fetch student marks detail */        
    //}

    ?>
	