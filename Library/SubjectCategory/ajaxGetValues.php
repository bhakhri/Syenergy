<?php
//-------------------------------------------------------
// Purpose: To get values of subject category from the database
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);   
UtilityManager::headerNoCache();
    
require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
$subjectCategoryManager =  SubjectCategoryManager::getInstance(); 
      //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    $orderBy = " $sortField $sortOrderBy";
    
    $subjectCategoryId = $REQUEST_DATA['id'];

    $condition = "sub.subjectCategoryId = '".$subjectCategoryId."'";
	$condition1 = " subjectCategoryId = '".$subjectCategoryId."'";
 
    $foundArray = $subjectCategoryManager->getSubjectListNew($condition);// to get subject details
	$foundArray1 = $subjectCategoryManager->getSubjectCategory($condition1);// to get category name
        $cnt=count($foundArray); 
	$cnt1=count($foundArray1); 
 
    

	for($i=0;$i<$cnt1;$i++){
         $categoryName = $foundArray1[$i]['categoryName'];
  	}
    
	
         $tableArray ="<div class='anyid' align='left'><b>Category&nbsp;:</b>&nbsp;".$categoryName."</div></br>
                  <table border='0px' width='100%' cellpadding='0px' cellspacing='2px' align='center' class='anyid' >
                    <tr class='rowheading'>  
                        <td align='left' width='5%'><b>#</b></td>
                        <td align='left' width='30%'><b>Subject Name</b></td>
                        <td align='left' width='15%'><b>Subject Code</b></td>
                        <td align='left' width='20%'><b>Subject Type</b></td>
                        <td align='left' width='15%'><b>Attendance Marked</b></td>
                        <td align='left' width='15%'><b>Marks Computed</b></td>
                        
                   </tr> ";

	if($cnt==0)
	{		$bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			$tableArray .="<tr cellspacing='2px' class='$bg'>
			<td colspan =6 align =center>No Data Found</td>
			</tr>";
	}
	else{
		for($i=0;$i<$cnt;$i++){
		       $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			$tableArray .="<tr class='$bg'>
					<td>".($i+1)."</td>
					<td>".$foundArray[$i]['subjectName']."</td>
					<td>".$foundArray[$i]['subjectCode']."</td>
					<td>".$foundArray[$i]['subjectTypeName']."</td>
					<td>".$foundArray[$i]['hasAttendance']."</td>
					<td>".$foundArray[$i]['hasMarks']."</td>
					</tr>";		
		}
      }



  		echo $tableArray;

 /*if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray[0]);
    }
    else {
      echo 0; // no record found
    }*/
?>
