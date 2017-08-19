<?php
//-------------------------------------------------------
// Purpose: To store the records of Employee in array from the database, pagination and search, delete 
// functionality
//
// Author : Parveen Sharma
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');  
    
	define('MODULE','EmployeeBusPass');
    define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    $employeeManager = EmployeeManager::getInstance();
    $commonQueryManager = CommonQueryManager::getInstance();   
    
    $busRouteArray=$commonQueryManager->getBusRoute('busRouteId');
    $busCnt=count($busRouteArray);
    
    /////////////////////////
    // to limit records per page    
    $page    = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////  
 
    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
             
    if (!empty($employeeCode)) {
        $conditionsArray[] = " emp.employeeCode LIKE '$employeeCode%' ";
    }
    
    if (!empty($employeeName)) {
        $conditionsArray[] = " emp.employeeName LIKE '%$employeeName%' ";
    }

    if (!empty($branchId)) {
        $conditionsArray[] = " emp.branchId IN ($branchId) ";
    }
    
    if (!empty($departmentId)) {
        $conditionsArray[] = " emp.departmentId IN ($departmentId) ";
    }

    if (!empty($instituteId)) {
        $conditionsArray[] = " emp.instituteId IN ($instituteId) ";
    }
    
    if (!empty($designationId)) {
        $conditionsArray[] = " emp.designationId IN ($designationId) ";
    }
    
    
    // Date of Birth (From - To)
    $birthDateF = $REQUEST_DATA['birthDateF'];
    $birthMonthF = $REQUEST_DATA['birthMonthF'];
    $birthYearF = $REQUEST_DATA['birthYearF'];
    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {
        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] =  " emp.dateOfBirth >= '$thisDate' ";
        }
    }
    
    $birthDateT = $REQUEST_DATA['birthDateT'];
    $birthMonthT = $REQUEST_DATA['birthMonthT'];
    $birthYearT = $REQUEST_DATA['birthYearT'];
    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {
        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] =  " (emp.dateOfBirth > '0000-00-00' AND emp.dateOfBirth <= '$thisDate') ";
        }
    }

    
    // Joining Date (From - To)
    $joiningDateF = $REQUEST_DATA['joiningDateF'];
    $joiningMonthF = $REQUEST_DATA['joiningMonthF'];
    $joiningYearF = $REQUEST_DATA['joiningYearF'];
    if (!empty($joiningDateF) && !empty($joiningMonthF) && !empty($joiningYearF)) {
        if (false !== checkdate($joiningMonthF, $joiningDateF, $joiningYearF)) {
            $thisDate = $joiningYearF.'-'.$joiningMonthF.'-'.$joiningDateF;
            $conditionsArray[] =  " emp.dateOfJoining >=  '$thisDate' ";
        }
    }
    
    $joiningDateT = $REQUEST_DATA['joiningDateT'];
    $joiningMonthT = $REQUEST_DATA['joiningMonthT'];
    $joiningYearT = $REQUEST_DATA['joiningYearT'];
    if (!empty($joiningDateT) && !empty($joiningMonthT) && !empty($joiningYearT)) {
        if (false !== checkdate($joiningMonthT, $joiningDateT, $joiningYearT)) {
            $thisDate = $joiningYearT.'-'.$joiningMonthT.'-'.$joiningDateT;
            $conditionsArray[] =  " (emp.dateOfJoining > '0000-00-00' AND emp.dateOfJoining <=  '$thisDate') ";
        }
    }
 
    
    // Leaving Date  (From - To)
    $leavingDateF = $REQUEST_DATA['leavingDateF'];
    $leavingMonthF = $REQUEST_DATA['leavingMonthF'];
    $leavingYearF = $REQUEST_DATA['leavingYearF'];
    if (!empty($leavingDateF) && !empty($leavingMonthF) && !empty($leavingYearF)) {
        if (false !== checkdate($leavingMonthF, $leavingDateF, $leavingYearF)) {
            $thisDate = $leavingYearF.'-'.$leavingMonthF.'-'.$leavingDateF;
            $conditionsArray[] =  " emp.dateOfLeaving >=  '$thisDate' ";   
        }
    }
    
    $leavingDateT = $REQUEST_DATA['leavingDateT'];
    $leavingMonthT = $REQUEST_DATA['leavingMonthT'];
    $leavingYearT = $REQUEST_DATA['leavingYearT'];
    if (!empty($leavingDateT) && !empty($leavingMonthT) && !empty($leavingYearT)) {
        if (false !== checkdate($leavingMonthT, $leavingDateT, $leavingYearT)) {
            $thisDate = $leavingYearT.'-'.$leavingMonthT.'-'.$leavingDateT;
            $conditionsArray[] =  " (emp.dateOfLeaving > '0000-00-00' AND emp.dateOfLeaving <=  '$thisDate') ";
        }
    }
        
    if (!empty($genderRadio)) {
        $conditionsArray[] = " emp.gender = '$genderRadio' ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " emp.cityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " emp.stateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " emp.countryId IN ($countryId) ";
    }
    
    if ($isMarried!='') {
        $conditionsArray[] = " emp.isMarried IN ($isMarried) ";
    } 
    
    if ($teachEmployee!='') {
        $conditionsArray[] = " emp.isTeaching IN ($teachEmployee) ";
    } 
    
    if ($qualification!='') {
        $conditionsArray[] = " emp.qualification LIKE '$qualification%' ";
    } 
             
    $conditions = '';        
    
    if (count($conditionsArray) > 0) {
        $conditions .= ' WHERE '.implode(' AND ',$conditionsArray);
    }

    if($conditions != "") {
       $conditions .= ' AND emp.isActive = 1 ';
    }
    else {
       $conditions .= ' WHERE emp.isActive = 1 ';
    }
    

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";         

    $conditions1 = " AND empBus.status = 1 "; 
    
    $totalArray = $employeeManager->getCountEmployeeBusPassList($conditions,$conditions1);
    $employeeRecordArray = $employeeManager->getEmployeeBusPassList($conditions,$limit,$orderBy,$conditions1);  
    $cnt = count($employeeRecordArray);

    for($i=0;$i<$cnt;$i++) {

        if($employeeRecordArray[$i]['employeeImage'] != ''){ 
            $File = STORAGE_PATH."/Images/Employee/".$employeeRecordArray[$i]['employeeImage'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Employee/'.$employeeRecordArray[$i]['employeeImage'];
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
        }
        
        $imgSrc = "<img src='".$imgSrc."?x=".rand(0,1000)."' width='20' height='20' id='employeeImageId' class='imgLinkRemove' />";
        $employeeRecordArray[$i]['imgSrc'] =  $imgSrc;
       
       
       if($employeeRecordArray[$i]['dateOfJoining']=='0000-00-00') {
          $doj = NOT_APPLICABLE_STRING;
       }
       else {
          $doj = UtilityManager::formatDate($employeeRecordArray[$i]['dateOfJoining']); 
       }
       
       if(strip_slashes($employeeRecordArray[$i]['employeeCode']) == '') {
         $employeeRecordArray[$i]['employeeCode']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['departmentAbbr']) == '') {
         $employeeRecordArray[$i]['departmentAbbr']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['designationName']) == '') {
         $employeeRecordArray[$i]['designationName']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['contactNumber']) == '') {
         $employeeRecordArray[$i]['contactNumber']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['permAddress']) == '') {
         $employeeRecordArray[$i]['permAddress']  = NOT_APPLICABLE_STRING;
       }
       
       $id= $employeeRecordArray[$i]['employeeId'];
       $busTotal = "<br><span id='divBusTotal".$id."' align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'></span>";
       $routeTotal = "<br><span id='divRouteTotal".$id."' align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'></span>";
       
       $checkall = "<input type='checkbox' name='chb[]' id='chb".$id."' onClick='checkDisable(".$id.")' value='".$id."'>";
        
       $busName = "<select size='1' style='width:160px;z-index:100;' class='selectfield' name='busRoute[]' onChange='autoPopulate(this.value,".$id.");' id='busRoute".$id."'>
                            <option value=''>Select</option>";
    
       $stoppage = "<select size='1' style='width:160px' class='selectfield' name='busStop[]'  id='busStop".$id."'>
                            <option value=''>Select</option>
                     </select>";
    
       $busNo = "<select size='1' style='width:160px' class='selectfield' name='busNo[]' onChange='getBusCount(this.value,".$id.");' id='busNo".$id."'>
                    <option value=''>Select</option>
                 </select>".$busTotal;
    
        
        for($j=0; $j<$busCnt; $j++) {
           if($employeeRecordArray[$i]['status']!="0") {      
              if($busRouteArray[$j]['busRouteId']==$employeeRecordArray[$i]['busRouteId']){
                $busName=$employeeRecordArray[$i]['routeCode'];
                $stoppage=$employeeRecordArray[$i]['stopName'];
                $busNo=$employeeRecordArray[$i]['busNo'];  
                break;
              }
              else{
                $busName .= '<option value="'.$busRouteArray[$j]['busRouteId'].'">'.$busRouteArray[$j]['routeCode'].'</option>';
              }
           }
           else{
             $busName .= '<option value="'.$busRouteArray[$j]['busRouteId'].'">'.$busRouteArray[$j]['routeCode'].'</option>';
           }
        }
        
        //if($j==$busCnt) {
        if($employeeRecordArray[$i]['status']=="") {     
           $checkall .= "<input type='hidden' class='inputbox' name='eEmployeeId[]' id='eEmployeeId".$id."' value='Y' />";   
           $busName .= '</select>'.$routeTotal;
        }
        else {
           $checkall .= "<input type='hidden' class='inputbox' name='eEmployeeId[]' id='eEmployeeId".$id."' value='N' />";   
        }
        
        $actionStr = NOT_APPLICABLE_STRING;
        if($employeeRecordArray[$i]['status']=="") {
           $validUpto = "<input type='text' maxlength='8' class='inputbox' style='width:90px' name='validUpto[]' id='validUpto".$id."' value='".strip_slashes($employeeRecordArray[$i]['validUpto'])."' />
                         <input type='hidden' class='inputbox'  style='width:90px' name='busEmployeeId[]' id='busEmployeeId".$id."' value='".$id."' /> ";
        }   
        else {
           $validUpto = UtilityManager::formatDate($employeeRecordArray[$i]['validUpto']); 
           //$actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$employeeRecordArray[$i]['busPassId'].',\'AddBusPass\',340,250);return false;"></a>'; 
           //$actionStr = "<a href=\"#\" name=\"bubble\" onclick=\"editBusPass("+$employeeRecordArray[$i]['busPassId']+"); return false;\" title='Cancel' style=\"color:red\">"+trim(document.getElementById('title').value)+"</a>";
           $actionStr = "<img src='".IMG_HTTP_PATH."/deactive.gif' border='0' alt='Cancel' title='Cancel' width='10' height='10' style='cursor:pointer' onclick='editBusPass(".$employeeRecordArray[$i]['employeeId'].",".$employeeRecordArray[$i]['busPassId']."); return false;' >";
        }
       
       
       $valueArray = array_merge(array(
                              'checkAll' => $checkall,
                              'srNo' => ($records+$i+1), 
                              'employeeName' => strip_slashes($employeeRecordArray[$i]['employeeName']),
                              'employeeCode' => strip_slashes($employeeRecordArray[$i]['employeeCode']),
                              'departmentAbbr' => strip_slashes($employeeRecordArray[$i]['departmentAbbr']),
                              'dateOfJoining' => $doj,
                              'designationName' => strip_slashes($employeeRecordArray[$i]['designationName']), 
                              'contactNumber' => strip_slashes($employeeRecordArray[$i]['contactNumber']),
                              'permAddress' => strip_slashes($employeeRecordArray[$i]['permAddress']),
                              'imgSrc' => $employeeRecordArray[$i]['imgSrc'],
                              'busName' => $busName,
                              'busNo' => $busNo,
                              'stoppage' => $stoppage,
                              'validUpto' => $validUpto,
                              'action1' => $actionStr));     
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitEmployeeList.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/14/09   Time: 6:22p
//Updated in $/LeapCC/Library/Icard
//validation format updated (null value checks updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/14/09   Time: 3:39p
//Updated in $/LeapCC/Library/Icard
//date checks updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/14/09   Time: 2:41p
//Updated in $/LeapCC/Library/Icard
//isActive checks updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:08p
//Created in $/LeapCC/Library/Icard
//initial checkin
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