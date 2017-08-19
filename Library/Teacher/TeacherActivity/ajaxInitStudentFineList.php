<?php
//-----------------------------------------------------------------------------------------------
// Purpose: To display student fine along with add,edit,delete,sorting and paging facility 
// Author : Rajeev Aggarwal
// Created on : (03.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineStudentMaster');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();

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

		$genName=" OR CONCAT(TRIM(firstName),' ',TRIM(lastName)) LIKE '".$genName."%'";
	}  
  
	return $genName;
	}
	$parsedName=parseName(trim($REQUEST_DATA['searchbox']));    //parse the name for compatibality
       
                                
                                
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

	   if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){

			$inService=1;  
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){

			$inService=0;  
       }
       else{

          $inService=-1;
       }

	  $approvedKey =  array_search(trim(ucfirst(strtolower ($REQUEST_DATA['searchbox']))),$statusCategoryArr);
	   if($approvedKey){

			$approveSearch = " OR status =".$approvedKey;
	   }
       $filter = ' AND ( rollNo LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR fineCategoryAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR firstName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR amount LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR noDues LIKE "'.$inService.'%" OR paid LIKE "'.$inService.'%" OR lastName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"'.$parsedName.' '.$approveSearch.'  )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineDate';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
	global $sessionHandler;
    $userId = $sessionHandler->getSessionVariable('UserId');

	$filter .="  AND fs.userId = $userId ";
    $totalArray             = $fineStudentManager->getTotalFineStudent($filter);
    $fineStudentRecordArray = $fineStudentManager->getFineStudentList($filter,$limit,$orderBy);
    $cnt = count($fineStudentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		
	   $fineStudentRecordArray[$i]['fineDate'] = UtilityManager::formatDate($fineStudentRecordArray[$i]['fineDate']);

	   $fineStudentRecordArray[$i]['status'] = $statusCategoryArr[$fineStudentRecordArray[$i]['status']]; 
	   
	   if($fineStudentRecordArray[$i]['paid']=='Yes'){

			$showlink = "--";
	   }
	   else{
	   
			$showlink = "<a href='#' onClick='editWindow(".$fineStudentRecordArray[$i]['fineStudentId'].",\"EditFineStudent\",360,250)' alt='Edit' title='Edit'><img src='".IMG_HTTP_PATH."/edit.gif' border='0' /></a>&nbsp;&nbsp;<a href='#' onClick='deleteFineCategory(".$fineStudentRecordArray[$i]['fineStudentId'].")' alt='Delete' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' /></a>";
	   }
       $valueArray = array_merge(array('action1' => $showlink , 'srNo' => ($records+$i+1) ),$fineStudentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitStudentFineList.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-11-06   Time: 3:54p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//In this if wrong roll no was entered then validations was not working
//during SAVE done in both admin and teacher login
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/09    Time: 10:47a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Updated module with dependency constraint
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:21p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//intial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:32p
//Updated in $/LeapCC/Library/Fine
//updated with yes no in student fine for paid field
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:30p
//Created in $/LeapCC/Library/Fine
//Intial checkin for fine student
?>