<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentReappear');
	define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
   
    $studentId = add_slashes($REQUEST_DATA['studentId']);   
    $classId   = add_slashes($REQUEST_DATA['classId']);   
   
    if($studentId=='') {
      $studentId = 0;
    }
    
    if($classId=='') {
      $classId = 0;
    }
   
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'reppearStatus';
    
    if ($sortField == 'subjectName') {
      $sortField1 = 'IF(IFNULL(subjectName,"")="",sub.subjectId, subjectName)';
    }
    else if ($sortField == 'subjectCode') {
      $sortField1 = 'IF(IFNULL(subjectCode,"")="",sub.subjectId, subjectCode)';
    }
    else if ($sortField == 'subjectTypeName') {
      $sortField1 = 'IF(IFNULL(subjectTypeName,"")="",sub.subjectId, subjectTypeName)';
    }
    else if ($sortField == 'reppearStatus') {
      $sortField1 = 'IF(IFNULL(reppearStatus,"")="",sub.subjectId, reppearStatus)';
    }
    else if ($sortField == 'detained') {
      $sortField1 = 'IF(IFNULL(detained,"")="",sub.subjectId, detained)';
      $sortField = "detained";
    }
    else {
      $sortField1 = 'IF(IFNULL(subjectName,"")="",sub.subjectId, subjectName)';
      $sortField = "subjectName";
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    
    if($classId=='') {
      $classId=0;  
    }
    
    $condition = " AND sc.classId = $classId";
    $reapperClassId = $classId;
    
    $studentRecordArray = $studentManager->getStudentReappearDetails($condition,$orderBy,$reapperClassId);
    $cnt = count($studentRecordArray);
    
    global $reppearStatusArr;
    
    $str1 = 0;
    $str2 = 0;
    $str3 = 0;
    
    for($i=0;$i<$cnt;$i++) {
        if($studentRecordArray[$i]['reppearStatus']!=1 && $studentRecordArray[$i]['reppearStatus']!=2) {
            $id = "reappearId".$studentRecordArray[$i]['subjectId'];
            $reappearId = $studentRecordArray[$i]['reappearId'];
            if($studentRecordArray[$i]['reappearId']=='') {
              $reappearId = -1;            
            }
            $check="";
            if($studentRecordArray[$i]['reppearStatus']==3) {
              $check="checked=checked";
            }
            $checkall = '<input type="checkbox" name="chb[]" '.$check.' value="'.$studentRecordArray[$i]['subjectId'].'">
                         <input type="hidden" readonly name="reapperId[]"  id="'.$id.'" value="'.$reappearId.'">';
        }
        else {
            $checkall = NOT_APPLICABLE_STRING;
        }
        $style = "";
        if($studentRecordArray[$i]['reppearStatus']!='Not Submitted') {
          if($studentRecordArray[$i]['reppearStatus']==1) {
             $style="style='color:green'";
          }
          else if($studentRecordArray[$i]['reppearStatus']==2) {
             $style="style='color:red'";
          }
          //else if($studentRecordArray[$i]['reppearStatus']==3) {
             //$style="style='color:green'";
          //}
          $studentRecordArray[$i]['reppearStatus'] =  "<span $style><b>".$reppearStatusArr[$studentRecordArray[$i]['reppearStatus']]."<b></span>";
        }
        
        if($studentRecordArray[$i]['detained']=='Y') {
           $checkall = NOT_APPLICABLE_STRING;
        }
        
        if($studentRecordArray[$i]['detained']=='Y') {  
          $style="style='color:red'";  
          $studentRecordArray[$i]['detained']="<span $style><b>Yes<b></span>";
        }                                     
        else {
          $studentRecordArray[$i]['detained']='No';  
        }
        
        $valueArray = array_merge(array('checkAll' => $checkall, 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        }
        
        if($studentRecordArray[$i]['assignmentStatus']!='0') {
           $str1 = $studentRecordArray[$i]['assignmentStatus'];
        }
        
        if($studentRecordArray[$i]['midSemesterStatus']!='0') {
           $str2 = $studentRecordArray[$i]['midSemesterStatus'];
        }
        
        if($studentRecordArray[$i]['attendanceStatus']!='0') {
           $str3 = $studentRecordArray[$i]['attendanceStatus']; 
        }
    }
    
    echo '{"assign":"'.$str1.'","midsem":"'.$str2.'","atte":"'.$str3.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitReappearList.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Library/Student
//function & validation message and format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/15/10    Time: 1:23p
//Updated in $/LeapCC/Library/Student
//student detained check update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/15/10    Time: 12:32p
//Updated in $/LeapCC/Library/Student
//validation & sorting format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/15/10    Time: 10:03a
//Updated in $/LeapCC/Library/Student
//format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/14/10    Time: 2:43p
//Updated in $/LeapCC/Library/Student
//validation format update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/14/10    Time: 1:52p
//Updated in $/LeapCC/Library/Student
//code update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/13/10    Time: 2:12p
//Updated in $/LeapCC/Library/Student
//subjectId base checks updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:06p
//Created in $/LeapCC/Library/Student
//initial checkin
//
//*****************  Version 14  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 6:13p
//Updated in $/LeapCC/Library/Student
//resolved issue 0002209,0002208,0002206,0002169,0002148,0002147,0002151,
//0002219,0002095
//
//*****************  Version 13  *****************
//User: Parveen      Date: 12/05/09   Time: 11:40a
//Updated in $/LeapCC/Library/Student
//student Photo added
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Library/Student
//worked on role to class
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Library/Student
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/22/09    Time: 3:40p
//Updated in $/LeapCC/Library/Student
//added Registration No. and Fee receipt no in student filter
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 6/24/09    Time: 1:36p
//Updated in $/LeapCC/Library/Student
//0000188: Find Student (Admin-CC) > Data is not displaying in correct
//order on “student list report print” window 
//
//0000183: Find Student - Admin > Search is not working properly in IE
//browser 
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/09/09    Time: 4:15p
//Updated in $/LeapCC/Library/Student
//Updated issues sent by Sachin sir dated 9thjune
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Library/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Library/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/14/09    Time: 12:25p
//Updated in $/LeapCC/Library/Student
//Updated search filter with permanent cityid, stateId and countryid
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/23/08   Time: 11:16a
//Updated in $/LeapCC/Library/Student
//Added group filter in student search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Library/Student
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Library/Student
//updated back button with class
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Library/Student
//updated as respect to subject centric
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Library/Student
//updated formatting and spacing
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Library/Student
//updated print reports
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Library/Student
//updated formatting and print reports
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Library/Student
//updated the formatting and other issues
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/09/08    Time: 3:09p
//Updated in $/Leap/Source/Library/Student
//changed label text
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:19p
//Updated in $/Leap/Source/Library/Student
//made ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:44p
//Updated in $/Leap/Source/Library/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/08    Time: 11:20a
//Created in $/Leap/Source/Library/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:56p
//Created in $/Leap/Source/Library/SubjectToClass
//intial checkin

?>
