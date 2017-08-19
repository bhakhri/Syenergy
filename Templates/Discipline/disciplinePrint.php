<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/DisciplineManager.inc.php");
    $disciplineManager = DisciplineManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    

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

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $recordArray[$i]['offenseDate']=UtilityManager::formatDate($recordArray[$i]['offenseDate']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Discipline Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="2%"', "align='center' ");
    $reportTableHead['studentName']         =   array('Name','width=15% align="left"', 'align="left"');
	$reportTableHead['rollNo']		        =	array('Roll No.','width=10% align="left"', 'align="left"');
    $reportTableHead['universityRollNo']    =   array('Univ Roll No.','width=10% align="left"', 'align="left"');
	$reportTableHead['className']		    =	array('Class','width="18%" align="left" ', 'align="left"');
    $reportTableHead['offenseAbbr']         =   array('Offence','width="8%" align="left" ', 'align="left"');
    $reportTableHead['offenseDate']         =   array('Date','width="10%" align="center" ', 'align="center"');
    $reportTableHead['reportedBy']          =   array('Reported By','width="12%" align="left" ', 'align="left"');
    $reportTableHead['remarks']             =   array('Remarks','width="15%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: disciplinePrint.php $
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