<?php
//-------------------------------------------------------
// Purpose: To store the records of Employee in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    //global $flag = 0;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','EmployeeMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $qryString = "&searchbox=".$REQUEST_DATA['searchbox'];  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='no' || strtolower(trim($REQUEST_DATA['searchbox']))=='n') {
            $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='yes' || strtolower(trim($REQUEST_DATA['searchbox']))=='ye') {
            $type=1;
       }
       else {
            $type=-1;
       }
       
       
        $filter = ' AND (emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         departmentId LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         contactNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         mobileNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emailAddress LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                         guestFaculty LIKE "%'.$type.'%" OR 
                         isTeaching LIKE "%'.$type.'%" )' ; 
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $employeeManager->getTotalEmployee($filter);
    $employeeRecordArray = $employeeManager->getEmployeeList($filter,$limit,$orderBy);  
    $cnt = count($employeeRecordArray);

	$labelArray = $employeeManager->getTimeTableLabel();
	$labelId = $labelArray[0]['timeTableLabelId'];
	$timeTableType = $labelArray[0]['timeTableType'];
	if (empty($labelId)) {
		$labelId = 0;
	}
	if (empty($timeTableType)) {
		$timeTableType = 0;
	}

     $formName = '';
       $guestFaculty = '';
    for($i=0;$i<$cnt;$i++) {
        // add employeeId in actionId to populate edit/delete icons in User Interface   
		if ($employeeRecordArray[$i]['isActive']==1) {
			$imgActive =  '<img src='.IMG_HTTP_PATH.'/active.gif border="0" alt="Active" title="Active" width="10" height="10" style="cursor:default">';	
		}
		else {
			$imgActive= '<img src='.IMG_HTTP_PATH.'/deactive.gif border="0" alt="Deactive" title="Deactive" width="10" height="10" style="cursor:default">';	
		}
      
	   
	   if ($employeeRecordArray[$i]['guestFaculty']==0) {
           $guestFaculty = 0; 
		   $formName = 'EditEmployeeDiv';
	   }
	   else if    ($employeeRecordArray[$i]['guestFaculty']==1)   {
		   $formName = 'EditGuestEmployeeDiv';
           $guestFaculty = 1;   
	   }
      // echo $guestFaculty;
     
       if($employeeRecordArray[$i]['isTeaching']=='Yes' AND $employeeRecordArray[$i]['isActive']==1) {
		   $actionStr = '';
		   if ($employeeRecordArray[$i]['guestFaculty']==0) {     
             
				$actionStr .= '<a href="#" alt="My Time Table" title="My Time Table"><img src="'.IMG_HTTP_PATH.'/calendar1.png" border="0" alt="My Time Table" width="15px" height="15px" onclick="printTimeTableEmployee('.$employeeRecordArray[$i]['employeeId'].','.$labelId.','.$timeTableType.');"></a>&nbsp;
                <a href="employeeInfo.php?Id='.$employeeRecordArray[$i]['employeeId'].$qryString.'&page='.$page.'&sortField='.$sortField.'&sortOrderBy='.$sortOrderBy.'" alt="Employee Detail" title="Employee Detail"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Employee Detail"></a>&nbsp;';
		 }   
         else 
         //if    ($employeeRecordArray[$i]['guestFaculty']==1) { 
                    {
                      $actionStr .= '<a href="#" alt="My Time Table" title="My Time Table"><img src="'.IMG_HTTP_PATH.'/calendar1.png" border="0" alt="My Time Table" width="15px" height="15px" onclick="printTimeTableEmployee('.$employeeRecordArray[$i]['employeeId'].','.$labelId.','.$timeTableType.');"></a>&nbsp'; 
                      } 
           $actionStr .= ' <a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editWindow('.$employeeRecordArray[$i]['employeeId'].',\''.$formName.'\',700,600,\''.$guestFaculty.'\');return false;" border="0">
            <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteEmployee('.$employeeRecordArray[$i]['employeeId'].');return false;"></a>';
        }
      
       else
         if ($employeeRecordArray[$i]['isTeaching']=='Yes' AND $employeeRecordArray[$i]['isActive']==1)
        {  
           $actionStr = ' <a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editWindow('.$employeeRecordArray[$i]['employeeId'].',\''.$formName.'\',700,600,\''.$guestFaculty.'\');return false;" border="0">
            <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteEmployee('.$employeeRecordArray[$i]['employeeId'].');return false;"></a>';
        }
        
        else {
              
            $actionStr ='<a href="employeeInfo.php?Id='.$employeeRecordArray[$i]['employeeId'].$qryString.'&page='.$page.'&sortField='.$sortField.'&sortOrderBy='.$sortOrderBy.'" alt="Detail" title="Detail">
                        <img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Detail"></a>&nbsp;  
            <a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editWindow('.$employeeRecordArray[$i]['employeeId'].',\''.$formName.'\',700,600,\''.$guestFaculty.'\');return false;" border="0">
            <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteEmployee('.$employeeRecordArray[$i]['employeeId'].');return false;"></a>';
        }         
        
       $valueArray = array_merge(array('active'=>$imgActive,'action1' => $actionStr, 'srNo' => ($records+$i+1) ),$employeeRecordArray[$i]);     

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
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 1:22p
//Updated in $/LeapCC/Library/Employee
//resolved issue 1623
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/Employee
//role permission,alignment, new enhancements added 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/07/09    Time: 9:48a
//Updated in $/LeapCC/Library/Employee
//alignment, formatting, conditions updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/30/09    Time: 5:36p
//Updated in $/LeapCC/Library/Employee
//validation, conditions, personal information formatting updated 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Employee
//formatting, conditions, validations updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Library/Employee
//validation, formatting, themes base css templates changes
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Library/Employee
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/09    Time: 11:58a
//Updated in $/LeapCC/Library/Employee
//fixed bug send by sachin sir
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Employee
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/21/08   Time: 7:22p
//Updated in $/Leap/Source/Library/Employee
//modified for active or deactive
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:40p
//Updated in $/Leap/Source/Library/Employee
//define access file
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/25/08    Time: 6:08p
//Updated in $/Leap/Source/Library/Employee
//fixed bug
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:00p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Library/Employee
//modification in employee in templates & functions
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/04/08    Time: 12:01p
//Created in $/Leap/Source/Library/Employee
//add ajax files for list of employee & delete employee
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
  
?>