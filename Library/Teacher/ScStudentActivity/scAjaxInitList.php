<?php
//Subject Centric
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();
    
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
        $filter =$filter." AND ssc.subjectId=".trim($REQUEST_DATA['subject']); 
    }
    if(trim($REQUEST_DATA['section'])!=""){
        $filter =$filter." AND ssc.sectionId=".trim($REQUEST_DATA['section']); 
    }
    if(trim($REQUEST_DATA['classes'])!=""){
        $filter =$filter." AND c.classId=".trim($REQUEST_DATA['classes']); 
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
// $History: scAjaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 10/21/08   Time: 4:26p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added functionility for partial roll no search
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/19/08    Time: 4:22p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/18/08    Time: 7:33p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/18/08    Time: 5:15p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:35p
//Created in $/Leap/Source/Library/Teacher/ScStudentActivity
?>
