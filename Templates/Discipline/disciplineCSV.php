<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/DisciplineManager.inc.php");
    $disciplineManager = DisciplineManager::getInstance();

    $conditionsArray = array();
    $qryString = "";
    

   //to parse csv values    
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
		 $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
          return $comments; 
         }
    }
    
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
    

    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $conditions = ''; 
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

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $disciplineManager->getDisciplineList($filter,$limit,$orderBy);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	print_r($recordArray);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $recordArray[$i]['offenseDate']=UtilityManager::formatDate($recordArray[$i]['offenseDate']);
		//$recordArray[$i]['reportedBy']=html_entity_decode($recordArray[$i]['reportedBy']) ;
		//$recordArray[$i]['remarks']=htmlentities($recordArray[$i]['remarks']) ;
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	$csvData = '';
	//$csvData .= "Sr, Name, Code, Abbr, Weightage.Amt, Weightage.Per, Eva.Criteria , University, Degree \n";
    $csvData .= "Sr, Name, Roll No.,Univ Roll No., Class, Offence, Date, Reported By, Remarks \n";
	foreach($valueArray as $record) {
		//$remarks = parseCSVComments($record['remarks']);
	    $remarks =" ".html_entity_decode($record['remarks']);
		$record['reportedBy']=" ".html_entity_decode($record['reportedBy']);
	    $csvData .= $record['srNo'].', '.$record['studentName'].', '.$record['rollNo'].', '.$record['universityRollNo'].', '.$record['className'].', '.$record['offenseAbbr'].', '.$record['offenseDate'].','.$record['reportedBy'].','.$remarks;
		$csvData .= "\n";
	}
	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="disciplineReport.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: disciplineCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 3:46p
//Updated in $/LeapCC/Templates/Discipline
//added university roll no.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Created in $/LeapCC/Templates/Discipline
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
?>