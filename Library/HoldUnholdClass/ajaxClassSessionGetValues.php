<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/HoldUnholdClassManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $HoldUnholdClassManager = HoldUnholdClassManager::getInstance(); 

	
    $degreeId = trim($REQUEST_DATA['degreeId']);
    $branchId = trim($REQUEST_DATA['branchId']); 
    $batchId = trim($REQUEST_DATA['batchId']); 

   
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 10000;  
    $limit      = ' LIMIT '.$records.',10000';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $orderBy = " $sortField $sortOrderBy";
        
    $condition='';
    $foundArray = $HoldUnholdClassManager->getSessionClasses($batchId,$branchId,$degreeId,$condition,$orderBy); 
	$cnt = count($foundArray);
	
    $allClassId = "";
	for($i=0;$i<$cnt; $i++) {
	  if($allClassId!='') {
	    $allClassId .= ",";
	  }
	  $allClassId .= $foundArray[$i]['classId'];
	}   
    
    
    for($i=0;$i<$cnt;$i++) {
        $classId =  $foundArray[$i]['classId'];
        $studyPeroid=  $foundArray[$i]['studyPeriodId'];
        $className =  $foundArray[$i]['className'];
        
        $chk1='';
        $chk2='';
        $chk3='';
        $chk4='';
        $span1="<span id='spanAtt$classId'>Unheld</span>";
        $span2="<span id='spanMarks$classId'>Unheld</span>";
        $span3="<span id='spanFinal$classId'>Unheld</span>";
        $span4="<span id='spanGrade$classId'>Unheld</span>";
        if($foundArray[$i]['holdAttendance']=='1') {
          $chk1="checked='checked'";
          $span1="<span id='spanAtt$classId'>Held</span>"; 
        }
        if($foundArray[$i]['holdTestMarks']=='1') {
          $chk2="checked='checked'"; 
          $span2="<span id='spanMarks$classId'>Held</span>"; 
        }
        if($foundArray[$i]['holdFinalResult']=='1') {
          $chk3="checked='checked'";  
          $span3="<span id='spanFinal$classId'>Held</span>"; 
        }
        if($foundArray[$i]['holdGrades']=='1') {
          $chk4="checked='checked'"; 
          $span4="<span id='spanGrade$classId'>Held</span>"; 
        }                      
                              
        $attendance = "<input type='checkbox' onclick='getHold($classId,\"A\");' $chk1 name='chbattendance[]' id='chbattendance_".$classId."' value='$classId'>".$span1;
	    $marks = "<input type='checkbox' onclick='getHold($classId,\"M\");' $chk2 name='chbmarks[]' id='chbmarks_".$classId."' value='$classId'>".$span2; 
	    $finalResult = "<input type='checkbox' onclick='getHold($classId,\"F\");' $chk3 name='chbfinalResult[]' id='chbfinalResult_".$classId."' value='$classId'>".$span3; 
	    $grades = "<input type='checkbox' onclick='getHold($classId,\"G\");' $chk4 name='chbgrades[]' id='chbgrades_".$classId."' value='$classId'>".$span4; 
        $classChk = "<input type='hidden' name='chbclassId[]' id='chbclassId_".$classId."' value='$classId'>";

        $individualStudent = "<input type='image' src='".IMG_HTTP_PATH."/edit.gif' align='center' onClick='listIndividualStudentWindow(\"$classId\",\"$allClassId\",\"$className\"); return false;' alt='Click to Hold/Unhold Individual Student' title='Click to Hold/Unhold Individual Student'  />&nbsp";
	
        $valueArray = array_merge(array('attendance' => $attendance.$classChk,
                                        'marks' => $marks,
		                                'finalResult' => $finalResult,
		                                'grades' => $grades,
					                    'individualStudent' => $individualStudent,
                                        'srNo' => ($records+$i+1),
                                        'studyPeriodName'=>$studyPeroid),
                                        $foundArray[$i]);
        if(trim($json_val)=='') {
             $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($foundArray).'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
