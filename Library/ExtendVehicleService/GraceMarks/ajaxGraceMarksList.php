<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GraceMarks');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GraceMarksManager.inc.php");
    $graceMarksManager = GraceMarksManager::getInstance();

    
    $graceMarksFormat = $REQUEST_DATA['graceMarksFor'];
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    
    //creates the condition                                                                                                                          
    $conditions ='';
    $conditions = " AND ttm.classId=".$REQUEST_DATA['class1']." 
                    AND ttm.subjectId=".$REQUEST_DATA['subject'];
    
    if($REQUEST_DATA['group']!='-1') {
      $conditions .=" AND sg.groupId=".$REQUEST_DATA['group'];
    }  
    
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
      $conditions .=' AND s.rollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%"';
    }

    // Total Marks Calculation
    $finalCondition = $conditions." AND ttm.conductingAuthority IN (1,2,3) ";    
    $graceMarksRecordArray = $graceMarksManager->getGraceMarksList($finalCondition,$orderBy);
    $cnt = count($graceMarksRecordArray);
    
    
    // Internal Marks Calculation
    $conditionMks =  $conditions." AND ttm.conductingAuthority IN (1,3) ";
    $intMksArray = $graceMarksManager->getGraceMarksList($conditionMks,$orderBy);   
    
    
    // External Marks Calculation
    $conditionMks =  $conditions." AND ttm.conductingAuthority IN (2) ";    
    $extMksArray = $graceMarksManager->getGraceMarksList($conditionMks,$orderBy);   
    
   $srNoUp=0;
	$srNoDown=0;
    for($i=0;$i<$cnt;$i++) {
    	
    	
       if($i==0) {
    	$srNoUp=0;
		$srNoDown=1;
       }	
	   else {
	   	$srNoUp=$i-1;
		$srNoDown=$i+1;
	   }	
       $studentId=$graceMarksRecordArray[$i]['studentId']; 
       $totalMaxMks = $graceMarksRecordArray[$i]['maxMarks'];
       $max=0;
     
          
       // Marks Scored
       $marksScored =$graceMarksRecordArray[$i]['marksScored'];   
       $graceMarksRecordArray[$i]['intMarks']=NOT_APPLICABLE_STRING;
       $graceMarksRecordArray[$i]['extMarks']=NOT_APPLICABLE_STRING;
       
       $totalGraceMarks='0';
       $g1='0';
       $g2='0';
       $g3='0';
       $find1 ='';
       $find2 ='';
       
       // Max Marks and Grace Marks
       $g3 = $graceMarksRecordArray[$i]['totalGraceMarks'];    
       $max3 =$graceMarksRecordArray[$i]['maxMarks'];   
       
       
       for($k=0;$k<count($intMksArray);$k++) {
         if($intMksArray[$k]['studentId'] == $studentId) {
           $graceMarksRecordArray[$i]['intMarks']= $intMksArray[$k]['marksScored'];    
           $max1 = $intMksArray[$k]['maxMarks'];
           $g1= $intMksArray[$i]['internalGraceMarks'];    
           $find1='1';
           break;
         }
       }
       
       for($k=0;$k<count($extMksArray);$k++) {
         if($extMksArray[$k]['studentId'] == $studentId) {
           $graceMarksRecordArray[$i]['extMarks']= $extMksArray[$k]['marksScored'];    
           $find2='2';  
           $max2 = $extMksArray[$k]['maxMarks'];
           $g2= $extMksArray[$i]['externalGraceMarks'];    
           break;
         }
       }        
       
       $marksScored =  $graceMarksRecordArray[$i]['marksScored'];
       
       $ttVal = '';
       if($graceMarksFormat=='3') {    
         $graceMarksEnter1 = $g1;
         $graceMarksEnter2 = $g2; 
         
         $ttVal = $g3;
         $max =$max3;
       }
       else if($graceMarksFormat=='1') {    
         $graceMarksEnter1 = $g2;
         $graceMarksEnter2 = $g3; 
         $max =$max1;
         $ttVal = $g1;
       }
       else if($graceMarksFormat=='2') {    
         $graceMarksEnter1 = $g1;
         $graceMarksEnter2 = $g3; 
         $max =$max2;
         $ttVal = $g2;
       }
       
       $totalGraceMarks = doubleval($graceMarksEnter1)+doubleval($graceMarksEnter2);
       if($totalGraceMarks=='') {
         $totalGraceMarks='0';  
       }
         
       
       if($ttVal<=0) {
         $ttVal='';  
       }
       
       if($max=='') {
         $max='0';  
       }
       
       if($gmarks=='') {
         $gmarks='0';  
       }
       
       if($max=='') {
         $max='0';  
       }
       
       if($totalMaxMks=='') {
         $totalMaxMks='0';  
       }
      
       $mksInt = $graceMarksRecordArray[$i]['intMarks']+$g3;
       $mksExt = $graceMarksRecordArray[$i]['extMarks']+$g3;  
       $mksTot = $graceMarksRecordArray[$i]['marksScored']+$graceMarksEnter1+$graceMarksEnter2;  
      
       $ttGraceMarks = '<input type="text" name="graceMarks" id="graceMarks'.$i.'" value="'.$ttVal.'" class="inputbox" style="width:40px" onkeyup="alertData(event,'.$srNoUp.','.$srNoDown.','.$i.')" />
                        <input type="hidden" name="finalInternal" id="finalInternal'.$i.'"  value="'.$mksInt.'" />
                        <input type="hidden" name="finalExternal" id="finalExternal'.$i.'"  value="'.$mksExt.'" />
                        <input type="hidden" name="finalTotal" id="finalTotal'.$i.'"  value="'.$mksTot.'" />
                        
                        
                        <input type="hidden" name="previousGraceMarks" id="previousGraceMarks'.$i.'"  value="'.$totalGraceMarks.'" />
                        <input type="hidden" name="maxMarks" id="maxMarks'.$i.'"  value="'.$max.'" />
                        <input type="hidden" name="markScored" id="markScored'.$i.'"  value="'.$graceMarksRecordArray[$i]['marksScored'].'" />
                        <input type="hidden" name="maxMarkScored" id="maxMarkScored'.$i.'"  value="'.$max.'" />
                        <input type="hidden" name="students" id="students'.$i.'"  value="'.$studentId.'" />
                        <input type="hidden" name="totalMaxMarks" id="totalMaxMarks'.$i.'"  value="'.$totalMaxMks.'" />';
       
       if($graceMarksFormat=='1') { 
          if($find1=='') {
            $ttGraceMarks = NOT_APPLICABLE_STRING;  
          } 
       }
       else if($graceMarksFormat=='2') { 
          if($find2=='') {
            $ttGraceMarks = NOT_APPLICABLE_STRING;  
          }  
       }
       
       $gmarks = $graceMarksRecordArray[$i]['finalGraceMarks'] ;
       $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                       'newMarks'=>'<span name="newMarks" id="newMarks'.$i.'">'.abs($gmarks + $graceMarksRecordArray[$i]['marksScored']).'</span>', 
                                       'graceMarksEnter1' => $graceMarksEnter1,
                                       'graceMarksEnter2' => $graceMarksEnter2,
                                       'ttGraceMarks' => $ttGraceMarks),
                                       $graceMarksRecordArray[$i]);


       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
	   
    }

    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
