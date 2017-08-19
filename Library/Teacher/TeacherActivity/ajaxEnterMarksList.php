<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student marks
//
// Author : Dipanjan Bbhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EnterAssignmentMarksMaster');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;

    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (sub.subjectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    */
    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';

	$sortField = $REQUEST_DATA['sortField'];

    if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="rollNo";
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
    $testMarksRecordArray=$teacherManager->getTestMarksList($filter,$limit,$orderBy);

	/*if($REQUEST_DATA['sortField'] == 'rollNo') {
		$sortField = 'numericRollNo';
	}*/

    $cnt = count($testMarksRecordArray);  //count of student records
    $testMarksRecordCount = count($testMarksRecordArray);  //count of test marks

	/*if($REQUEST_DATA['sortField'] == 'rollNo') {
		$sortField = 'rollNo';
	}*/

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
       if($testMarksRecordArray[$i]['testMarksId']!=-1){ //if studentId exist in test_marks table
        if($testMarksRecordArray[$i]['isMemberOfClass']=="1"){
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
         'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
		 'regNo' =>strip_slashes($testMarksRecordArray[$i]['regNo']),
         'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
         'marksScored'=>'<input type="textbox" class="inputbox" onchange="setGlobalEditFlag(1);" style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="0"?"readOnly=true":"").' value="'.strip_slashes($testMarksRecordArray[$i]['marksScored']).'"   onkeyup="checkNumber(this.value,this.id,event,'.$srNoUp.','.$srNoDown.');" onclick="marksAction();hideText(this.id);" onblur="getSavedTextBoxData(this.id);" tabIndex='.($i+13).' onFocus="this.parentNode.parentNode.className=\'specialHighlight\';" onBlur="this.parentNode.parentNode.className=this.parentNode.parentNode.getAttribute(\'value\')" /><input type="hidden" name="hiddenMarks" id="hiddenMarks'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="0"?"readOnly=true":"").' value="'.strip_slashes($testMarksRecordArray[$i]['marksScored']).'" >',

         'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="1"?"checked=checkd":"").' onclick="disableMarks('.$i.');"  >',
         'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" >'.'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         //,'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         ));
        }
       else{
          $valueArray = array_merge(array('srNo' => ($records+$i+1),
          'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
          'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
          'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
          'marksScored'=>'<input type="textbox" class="inputbox" onchange="setGlobalEditFlag(1);"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" value="0" readOnly="true"   onkeyup="checkNumber(this.value,this.id,event,$srNoUp,$srNoDown);" onblur="getTextBoxData(this.id);" onclick="marksAction();hideText(this.id);" tabIndex='.($i+13).' onBlur="this.parentNode.parentNode.className=this.parentNode.parentNode.getAttribute(\'value\')" /><input type= "hidden" name="hiddenMarks" id="hiddenMarks'.$i.'" value="0" >',

          'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" disabled="disabled"  onclick="disableMarks('.$i.');"  >',
          'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'"  onclick="mocAction('.$i.');" >'.'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
          ,'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         ));
        }
       }

      else{ //if studentId does not exist in test_marks table

         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
         'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
		 'regNo' =>strip_slashes($testMarksRecordArray[$i]['regNo']),
         'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
         'marksScored'=>'<input type="textbox" class="inputbox" onchange="setGlobalEditFlag(1);"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" value="0" onkeyup="checkNumber(this.value,this.id,event,'.$srNoUp.','.$srNoDown.');" onblur="getTextBoxData(this.id);" onclick="marksAction();hideText(this.id);" tabIndex='.($i+13).' onBlur="this.parentNode.parentNode.className=this.parentNode.parentNode.getAttribute(\'value\')" /><input type= "hidden" name="hiddenMarks" id="hiddenMarks'.$i.'" value="0">',
         'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" checked="checked"  onclick="disableMarks('.$i.');"  >',
         'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" >'.'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
         ,'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId'].'" >'
        ));
      }

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxEnterMarksList.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 13/04/10   Time: 17:03
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done llrit enhancements
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 21/12/09   Time: 15:08
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Changes period display in daily attendance module [ Pediod No (Period
//Slot) ] and changed default sorting logic in test marks module in admin
//and teacher section
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 3/12/09    Time: 11:02
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made UI related changes :  Added alert for unsaved data
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 1/12/09    Time: 17:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made UI changes in test marks module in teacher module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/09/09    Time: 15:38
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//bug id---00001467
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/24/09    Time: 6:30p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified as per sorting university roll no. Leet & isLett
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/09/08   Time: 3:09p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected Marks modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/08    Time: 8:11p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 1:32p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/09/08    Time: 10:51a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/06/08    Time: 10:41a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
