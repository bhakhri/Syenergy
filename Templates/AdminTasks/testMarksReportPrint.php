<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To print the records of student marks
//
// Author : Kavish Manjkhola
// Created on : (4/26/2011)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TestMarks');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $teacherManager = AdminTasksManager::getInstance();
	global $sessionHandler;

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;

    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';

	$sortField = $REQUEST_DATA['sortField'];

    if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="numericRollNo";
    }
    elseif($sortField=="universityRollNo"){
        //$sortField2="isLeet,universityRollNo";
        $sortField2=" isLeet,IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }
    elseif($sortField=="marks"){
        $sortField2="tm.marksScored";
    }
	else {
		//$sortField2="isLeet,universityRollNo";
        $sortField2=" isLeet,IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
	}
	/*
    if($sortField=="present"){
        $sortField="tm.isPresent";
    }
    if($sortField=="memberOfClass"){
        $sortField="tm.isMemberOfClass";
    }
	*/

	$orderBy = " $sortField2 $sortOrderBy";

    $totalArray = $teacherManager->getTotalTestMarks($filter);
    $testMarksRecordArray=$teacherManager->getStudenttMarksList($filter,$limit,$orderBy);


	/*if($REQUEST_DATA['sortField'] == 'rollNo') {
		$sortField = 'numericRollNo';
	}*/

    $cnt = count($testMarksRecordArray);  //count of student records
    $testMarksRecordCount = count($testMarksRecordArray);  //count of test marks

	/*if($REQUEST_DATA['sortField'] == 'rollNo') {
		$sortField = 'rollNo';
	}*/

	$className='';
	$periodName ='';
	$subjectName ='';
	$subjectCode = '';
	$employeeName ='';
    for($i=0;$i<$cnt;$i++) {
	   $className = $testMarksRecordArray[$i]['className'];
	   $periodName = $testMarksRecordArray[$i]['periodName'];
	   $subjectName = $testMarksRecordArray[$i]['subjectName'];
	   $subjectCode = $testMarksRecordArray[$i]['subjectCode'];
   	   $employeeName = $testMarksRecordArray[$i]['employeeName'];
       if($testMarksRecordArray[$i]['testMarksId']!=-1){ //if studentId exist in test_marks table
        if($testMarksRecordArray[$i]['isMemberOfClass']=="1"){
         $valueArray[] = array_merge(array('srNo' => ($records+$i+1),
         'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
         'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
		 'regNo' =>strip_slashes($testMarksRecordArray[$i]['regNo']),
         'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
         'marksScored'=>'<input type="textbox" class="inputbox"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="0"?"readOnly=true":"").' value="'.strip_slashes($testMarksRecordArray[$i]['marksScored']).'"   onkeyup="checkNumber(this.value,this.id);" onblur="getTextBoxData(this.id);" onclick="marksAction();hideText(this.id);" tabIndex='.($i+13).'  onFocus="this.parentNode.parentNode.className=\'specialHighlight\';" onBlur="this.parentNode.parentNode.className=this.parentNode.parentNode.getAttribute(\'value\')" />',
         'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="1"?"checked=checkd":"").' onclick="disableMarks('.$i.');"  >',
         'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" >'.'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         //,'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         ));
        }
       else{
          $valueArray[] = array_merge(array('srNo' => ($records+$i+1),
          'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
          'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
		  'regNo' =>strip_slashes($testMarksRecordArray[$i]['regNo']),
          'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
          'marksScored'=>'<input type="textbox" class="inputbox"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" value="0" readOnly="true"   onkeyup="checkNumber(this.value,this.id);" onblur="getTextBoxData(this.id);" onclick="marksAction();hideText(this.id);" tabIndex='.($i+13).' onFocus="this.parentNode.parentNode.className=\'specialHighlight\';" onBlur="this.parentNode.parentNode.className=this.parentNode.parentNode.getAttribute(\'value\')" />',
          'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'"  onclick="disableMarks('.$i.');"  >',
          'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'"  onclick="mocAction('.$i.');" >'.'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
          //,'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         ));
        }
       }

      else{ //if studentId does exist in test_marks table

         $valueArray[] = array_merge(array('srNo' => ($records+$i+1),
         'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
         'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
		 'regNo' =>strip_slashes($testMarksRecordArray[$i]['regNo']),
         'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
         'marksScored'=>'<input type="textbox" class="inputbox" style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" value="0"  onkeyup="checkNumber(this.value,this.id);"  onblur="getTextBoxData(this.id);" onclick="marksAction();hideText(this.id);" tabIndex='.($i+13).' onFocus="this.parentNode.parentNode.className=\'specialHighlight\';" onBlur="this.parentNode.parentNode.className=this.parentNode.parentNode.getAttribute(\'value\')" />',
         'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" checked="checked"  onclick="disableMarks('.$i.');"  >',
         'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" >'.'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         //,'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
        ));
      }
    }
	$academicYear = $sessionHandler->getSessionVariable('SessionName');

	$tableData = "
					<table border='0' cellpading='0px' cellspacing='0' width='100%'>
						<tr>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Name of Teahcer:</b></td>
							<td style='padding-left:10px' class='contenttab_internal_rows'>$employeeName</td>
						<tr>
						<tr>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Programme:</b></tb>
							<td style='padding-left:10px' class='contenttab_internal_rows'>$className</td>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Semester:</b></td>
							<td style='padding-left:10px' class='contenttab_internal_rows'>$periodName </td>
					    </tr>
						<tr>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Academic Year:</b></td>
							<td style='padding-left:10px' class='contenttab_internal_rows'>$academicYear</td>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Course Name:</b></td>
							<td style='padding-left:10px' class='contenttab_internal_rows'>$subjectName</td>
						</tr>
						<tr>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Course Code:</b></td>
							<td style='padding-left:10px' class='contenttab_internal_rows'>$subjectCode</td>
							<td style='padding-left:10px' class='contenttab_internal_rows'><b>Credit Hrs:</b></td>
							<td style='padding-left:10px' class='contenttab_internal_rows'></td>
						</tr>
					</table>
				";
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Test Marks Report ');
	$reportManager->setReportInformation($tableData);
	//$reportManager->setReportHeading();

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="1%" align="left"', "align='left'");
    $reportTableHead['studentName']	=    array('Name',' width=25% align="left" ','align="left" ');
    $reportTableHead['rollNo']	=    array('RollNo',' width="10%" align="left" ','align="left"');
	$reportTableHead['universityRollNo']		=    array('Univ.RollNo',' width="15%" align="left" ','align="left"');
	$reportTableHead['regNo']		=    array('Regd. No.',' width="10%" align="left" ','align="left"');
    $reportTableHead['marksScored']		=    array('Marks',' width="10%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
	?>