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
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    if($sortField=="marks"){
        $sortField="tm.marksScored";
    }
    if($sortField=="present"){
        $sortField="tm.isPresent";
    }
    if($sortField=="memberOfClass"){
        $sortField="tm.isMemberOfClass";
    }
     $orderBy = " $sortField $sortOrderBy";   
    
    $totalArray = $teacherManager->getTotalTestMarks($filter);
    $testMarksRecordArray=$teacherManager->getTestMarksList($filter,$limit,$orderBy);  
//    print_r($testMarksRecordArray);
    
    $cnt = count($testMarksRecordArray);  //count of student records
    //$testMarksRecordCount = count($testMarksRecordArray);  //count of test marks

    for($i=0;$i<$cnt;$i++) {
       if($testMarksRecordArray[$i]['testMarksId']!=-1){ //if studentId exist in test_marks table   
        if($testMarksRecordArray[$i]['isMemberOfClass']=="1"){
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
         'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
         'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
         'marksScored'=>'<input type="textbox"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="0"?"readOnly=true":"").' value="'.strip_slashes($testMarksRecordArray[$i]['marksScored']).'"   onkeyup="checkNumber(this.value,this.id);" onclick="marksAction();" tabIndex='.($i+13).' >',
         'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" '.(($testMarksRecordArray[$i]['isPresent'])=="1"?"checked=checkd":"").' onclick="disableMarks('.$i.');"  >',
         'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" >',
         'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId']."~".$testMarksRecordArray[$i]['classId'].'" >'
         ));
        }
       else{
          $valueArray = array_merge(array('srNo' => ($records+$i+1),
          'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
          'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
          'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
          'marksScored'=>'<input type="textbox"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" value="0" readOnly="true"   onkeyup="checkNumber(this.value,this.id);" onclick="marksAction();" tabIndex='.($i+13).' >',
          'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'"  onclick="disableMarks('.$i.');"  >',
          'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'"  onclick="mocAction('.$i.');" >',
          'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId']."~".$testMarksRecordArray[$i]['classId'].'" >'
         ));
        }  
       }
       
      else{ //if studentId does exist in test_marks table
      
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         'studentName' =>strip_slashes($testMarksRecordArray[$i]['studentName']),
         'rollNo' =>strip_slashes($testMarksRecordArray[$i]['rollNo']),
         'universityRollNo' =>strip_slashes($testMarksRecordArray[$i]['universityRollNo']),
         'marksScored'=>'<input type="textbox"  style="width:50px;text-align:center;" name="imarks" id="imarks'.$i.'" value="0"   onkeyup="checkNumber(this.value,this.id);"  onclick="marksAction();" tabIndex='.($i+13).'>',
         'isPresent'=>'<input type="checkbox" name="ipre" id="ipre'.$i.'" checked="checked"  onclick="disableMarks('.$i.');"  >',
         'isMemberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" >',
         'stid'=>'<input type="hidden" name="stid" id="stid'.$i.'" value="'.$testMarksRecordArray[$i]['testMarksId']."~".$testMarksRecordArray[$i]['studentId']."~".$testMarksRecordArray[$i]['classId'].'" >'
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
// $History: scAjaxEnterMarksList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
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
