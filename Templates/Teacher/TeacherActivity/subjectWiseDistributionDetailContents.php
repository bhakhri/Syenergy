<?php 
//This file is used as printing version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    $classId    = trim($REQUEST_DATA['classId']);
    $groupId    = trim($REQUEST_DATA['groupId']);
    $subjectId  = trim($REQUEST_DATA['subjectId']);
    $testRange  = trim($REQUEST_DATA['interval']);
    $examType   = trim($REQUEST_DATA['examType']); 

    if($classId=='' or $testRange=='' or $subjectId=='' or $examType=='' or $groupId==''){
     echo 'Required Parameters Missing';
     die;
    }

//validating input data  
$tR=explode(',',$testRange);
$len1=count($tR);

for($i=0;$i<$len1;$i++){
    $tRange=explode('-',$tR[$i]);
    $len2=count($tRange);
    if($len2!=2){
        echo INVALID_MARKS_RANGE;
        die;
    }
    for($k=0;$k<$len2;$k++){
        if(!is_numeric(trim($tRange[$k]))){
           echo ENTER_NUMERIC_VALUE_FOR_MARKS_RANGE;
           die;
        }
    }
}
	
    //$studentRecordArray = $studentManager->getTestMarksDistributionDetailData($testIds,' AND tm.marksScored BETWEEN '.$tRange[0].' AND '.$tRange[1]);
    
    if(trim($REQUEST_DATA['rangeType'])==2){ //if absoulte comparison is required
      $conditions=' HAVING marksScored  BETWEEN '.$tRange[0].' AND '.$tRange[1];
      $conditionType='';
      $ct='';
    }
    else{
      $conditions=' HAVING per BETWEEN '.$tRange[0].' AND '.$tRange[1];
      $conditionType='( in percentage )';
      $ct='( in % )';
    }
    
    //whether to include grace marks
    if(trim($REQUEST_DATA['showGraceMarks'])==1){
        $showGraceMarks=1;
        $graceMarks='Yes';
    }
    else{
        $showGraceMarks=0;
        $graceMarks='No';
    }
    
    if($examType==1){
       $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (1,2,3)';
       $examTypeString='All';
    }
    else if($examType==2){
       $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (1,3)';
       $examTypeString='Inetrnal';   
    }
    else{
       $conductingAuthorityCondition=' AND ttm.conductingAuthority IN (2)';
       $examTypeString='External';   
    }
    
    
    $groupConditions =' AND sg.classId='.$classId.' AND sb.subjectId IN ('.$subjectId.')';
    if($groupId!='-1'){
     $groupConditions .=' AND sg.groupId='.$groupId; 
    }
    
    $studentRecordArray = $studentManager->getSubjectWiseDistributionDetailData($classId,$subjectId,$conditions,$showGraceMarks,$conductingAuthorityCondition,$groupConditions);
    $cnt = count($studentRecordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $TD=0;
	$valueArray = array();
    
    /*CALCULATE NO OF STUDENTS IN EACH RANGE*/
    if($cnt>0){
     $statiticsSting='';   
     /*
     $percentageArray=explode(',',UtilityManager::makeCSList($studentRecordArray,'per'));
     $start=intval($tRange[0]);
     $end=intval($tRange[1]);
     for($j=$start;$j<$end;$j++){
         $c=0;
         for($k=0;$k<$cnt;$k++){
           if($percentageArray[$k]==$j){
               $c++;
           }  
         }
        if($statiticsSting!=''){
            $statiticsSting .= ',';
        }
        if($j%5==0 && $statiticsSting!=''){
            $statiticsSting .= '<br/>';
        }
        $statiticsSting .=$c.' students got '.$j.$ct;
      }
      */
      if(trim($REQUEST_DATA['rangeType'])==2){
          $perArray=explode(',',UtilityManager::makeCSList($studentRecordArray,'marksScored'));
      }
      else{
          $perArray=explode(',',UtilityManager::makeCSList($studentRecordArray,'per'));
      }
      sort($perArray);
      $percentageArray=array_count_values($perArray);
      foreach($percentageArray as $key=>$value){
        $statiticsSting .='<tr><td align="right" '.$reportManager->getReportDataStyle().'>'.$key.'</td><td align="right" '.$reportManager->getReportDataStyle().'>'.$value.'</tr>';
      }
      if($statiticsSting!=''){
          $statiticsSting ='<table border="1" cellspacing="0" class="reportTableBorder"  align="center"><tr><td align="right" '.$reportManager->getReportHeadingStyle().'>Marks'.$ct.'</td><td align="right" '.$reportManager->getReportHeadingStyle().'>Students</td>'.$statiticsSting.'</table>';
      }
    }
    
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$studentRecordArray[$i]);
    }
    

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Subject Wise Performance Detail Print'.$conditionType);
    $reportManager->setReportInformation("Time Table: $REQUEST_DATA[timeTableName] Subject Type: $REQUEST_DATA[subjectTypeName] Class: $REQUEST_DATA[className] Group: $REQUEST_DATA[groupName] <br/>Subject : ".$studentRecordArray[0]['subjectCode']."  Range : $testRange<br/>Show Grace Marks : ".$graceMarks."<br/>Exam Type : ".$examTypeString);

	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%"', "align='center' ");
    $reportTableHead['studentName']         =   array('Name','width=12% align="left"', 'align="left"');
	$reportTableHead['rollNo']			    =	array('Roll No.','width=10% align="left"', 'align="left"');
	$reportTableHead['universityRollNo']    =   array('Univ. Roll No.','width=10% align="left"', 'align="left"');
    $reportTableHead['marksScored']         =   array('Marks Scored','width="10%" align="right" ', 'align="right"');
    $reportTableHead['maxMarks']            =   array('Max. Marks','width="10%" align="right" ', 'align="right"');
    $reportTableHead['per']                 =   array('Percentage','width="10%" align="right" ', 'align="right"');

	$reportManager->setRecordsPerPage(30);
    //*********show statitics information***********
    $reportManager->setFirstPageText($statiticsSting);

	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: subjectWiseDistributionDetailContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/12/09    Time: 11:08
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Subject Wise Performance Graph"---Added the option of
//include/exclude grace marks in this report
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:15
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//created "Subject Wise Performance" report
?>