<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SearchStudentDisplay');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:19.09.2008
//-----------------------------------------------------------------------------------
function parseName($value){
    $name=explode(' ',$value);
    $genName="";
    $len= count($name);
    if($len > 0){
      for($i=0;$i<$len;$i++){
          if(trim($name[$i])!=""){
              if($genName!=""){
                  $genName =$genName ." ".$name[$i];
              }
             else{
                 $genName =$name[$i];
             } 
          }
      }
    }
  if($genName!=""){
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;

    //////
    /// Search filter /////  
    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       //$filter = ' AND (ct.cityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ct.cityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
       $filter ='';
    }
    */
    $filter="";

    if(trim($REQUEST_DATA['subject'])!=""){
        $filter =$filter." AND sc.subjectId=".trim($REQUEST_DATA['subject']); 
    }
    if(trim($REQUEST_DATA['group'])!=""){
        $filter =$filter." AND g.groupId=".trim($REQUEST_DATA['group']); 
    }
    if(trim($REQUEST_DATA['class'])!=""){
        $filter =$filter." AND c.classId=".trim($REQUEST_DATA['class']); 
    }
    if(trim($REQUEST_DATA['studentNameFilter'])!=""){
        $parsedName=parseName(trim($REQUEST_DATA['studentNameFilter']));    //parse the name for compatibality
        $filter =$filter." AND (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($REQUEST_DATA['studentNameFilter']))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($REQUEST_DATA['studentNameFilter']))."%'
                                  $parsedName
                               )"; 
    }
    if(trim($REQUEST_DATA['studentRollNo'])!=""){
        $filter =$filter." AND s.rollNo LIKE '".add_slashes(trim($REQUEST_DATA['studentRollNo']))."%'"; 
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getSearchTotalStudent($filter);
    $studentRecordArray = $teacherManager->getSearchStudentList($filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {

        $valueArray = array_merge(array('details' =>"<img src=\"".IMG_HTTP_PATH."/zoom.gif\" border=\"0\" title=\"Detail View\" alt=\"Details\" onclick=\"openUrl('".$studentRecordArray[$i]['studentId']."')\" />" , 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:37p
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Corrected Student Tabs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/21/08   Time: 4:26p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//Added functionility for partial roll no search
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/06/08    Time: 10:41a
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:19p
//Created in $/Leap/Source/Library/Teacher/StudentActivity
//Initial Checkin
?>
