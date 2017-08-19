<?php
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
    define('MODULE','DisciplineMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DisciplineManager.inc.php");
    $disciplineManager = DisciplineManager::getInstance();
    
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
      $genName=" OR CONCAT(TRIM(st.firstName),' ',TRIM(st.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
	 

   /* if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $parsedName=parseName(trim($REQUEST_DATA['searchbox']));    //parse the name for compatibality
       $nameFilter="  
                       (
                             TRIM(st.firstName) LIKE '%".htmlentities(add_slashes(trim($REQUEST_DATA['searchbox'])))."%' 
                              OR 
                             TRIM(st.lastName) LIKE '%".htmlentities(add_slashes(trim($REQUEST_DATA['searchbox'])))."%'
                             $parsedName
                       ) ";
					   
       $filter = ' AND ( st.rollNo LIKE "'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR st.universityRollNo LIKE "%'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR ';
	   $filter .= $nameFilter;

	   $filter .= ' LIKE "%'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR cl.className LIKE "%'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR off.offenseAbbr LIKE "'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR DATE_FORMAT(std.offenseDate,"%d-%b-%y") LIKE "%'.htmlentities(add_slashes($REQUEST_DATA['searchbox'])).'%" OR std.reportedBy LIKE "%'.htmlentities(add_slashes($REQUEST_DATA['searchbox'])).'%" OR std.remarks LIKE "%'.htmlentities(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }*/
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $parsedName=parseName(trim($REQUEST_DATA['searchbox']));    //parse the name for compatibality
       $nameFilter="  
                       (
                             TRIM(st.firstName) LIKE '%".htmlentities(add_slashes(trim($REQUEST_DATA['searchbox'])))."%' 
                              OR 
                             TRIM(st.lastName) LIKE '%".htmlentities(add_slashes(trim($REQUEST_DATA['searchbox'])))."%'
                             $parsedName
                       ) OR ";
					   
       $filter = ' AND ( st.rollNo LIKE "%'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR st.universityRollNo LIKE "%'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR ';
	   $filter .= $nameFilter;

	   $filter .=  'cl.className LIKE "%'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR off.offenseAbbr LIKE "'.trim(htmlentities(add_slashes($REQUEST_DATA['searchbox']))).'%" OR DATE_FORMAT(std.offenseDate,"%d-%b-%y") LIKE "%'.htmlentities(trim(add_slashes($REQUEST_DATA['searchbox']))).'%" OR std.reportedBy LIKE "%'.htmlentities(trim(add_slashes($REQUEST_DATA['searchbox']))).'%" OR std.remarks LIKE "%'.htmlentities(trim(add_slashes($REQUEST_DATA['searchbox']))).'%")';
	}

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray           = $disciplineManager->getTotalDiscipline($filter);
    $disciplineRecordArray = $disciplineManager->getDisciplineList($filter,$limit,$orderBy);
    $cnt = count($disciplineRecordArray);
    
    for($i=0;$i<$cnt;$i++) {

       $disciplineRecordArray[$i]['offenseDate']=UtilityManager::formatDate($disciplineRecordArray[$i]['offenseDate']);
       if(strlen($disciplineRecordArray[$i]['remarks'])>=90){
           $disciplineRecordArray[$i]['remarks']=substr($disciplineRecordArray[$i]['remarks'],0,87).'...';
       }
       $disciplineRecordArray[$i]['remarks']= str_replace(' ','&nbsp;',$disciplineRecordArray[$i]['remarks']);
       
       $valueArray = array_merge(array('action' => $disciplineRecordArray[$i]['disciplineId'] , 'srNo' => ($records+$i+1) ),$disciplineRecordArray[$i]);

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
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 3:46p
//Updated in $/LeapCC/Library/Discipline
//added university roll no.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/08/09   Time: 17:29
//Updated in $/LeapCC/Library/Discipline
//Corrected msg display in teacher dashboard
//and discipline module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Library/Discipline
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Library/Discipline
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:04
//Created in $/LeapCC/Library/Discipline
//Created 'Discipline' Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Library/Discipline
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Library/Discipline
//Created module 'Discipline'
?>
