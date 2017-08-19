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
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);  
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    require_once(MODEL_PATH . "/EmployeeLeaveCarryForwardManager.inc.php");
    $carryForwardManager = EmployeeLeaveCarryForwardManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sessionHandler->setSessionVariable('carryForwardEmployeeId','');
    $sessionHandler->setSessionVariable('carryForwardEmployeeId','');  
    

    $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                   <tr class='rowheading'>
                     <td width='2%'  class='searchhead_text'  align='left'><b><nobr>#</nobr></b></td>
                     <td width='14%'  class='searchhead_text'  align='left'><strong><nobr>Emp. Name</nobr></strong></td>
                     <td width='10%'  class='searchhead_text'  align='left'><strong><nobr>Emp. Code</nobr></strong></td>
                     <td width='13%' class='searchhead_text'  align='left'><strong><nobr>Designation</nobr></strong></td>
                     <td width='12%' class='searchhead_text'  align='left'><strong><nobr>Dt. of Joining</nobr></strong></td>
                     <td width='13%' class='searchhead_text'  align='left'><strong><nobr>Leave Type</nobr></strong></td>
                     <td width='6%' class='searchhead_text'  align='right'><strong><nobr>Allowed</nobr></strong></td>
                     <td width='6%' class='searchhead_text'  align='right'><strong><nobr>Taken</nobr></strong></td>
                     <td width='7%' class='searchhead_text'  align='right'><strong><nobr>Balance</nobr></strong></td>
                     <td width='12%' class='searchhead_text'  align='center'><strong><nobr><input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">Carry Forwd.</nobr></strong></td>
                   </tr>"; 
 
                                 
    // Set leave sets
    $leaveSetArray = $commonQueryManager->getLeaveSessionSetAdvData(' AND s.active=1 AND ls.isActive=1');
    
    // Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
      $leaveSessionDate=$leaveSessionArray[0]['sessionEndDate']; 
    }                                                                                                         
    
    if($leaveSessionId=='') {
      echo ACTIVE_LEAVE_SESSION;    
      die;  
    }
    
    
    // Next Session Date
    $leaveSessionDateCondition = " WHERE sessionStartDate > '$leaveSessionDate'";
    $leaveSessionDateArray = $commonQueryManager->getLeaveSessionList($leaveSessionDateCondition," sessionStartDate ASC");   
    $nextLeaveSessionId='';
    if($leaveSessionDateArray[0]['leaveSessionId']!='') {
      $nextLeaveSessionId=$leaveSessionDateArray[0]['leaveSessionId']; 
    }   
    
    if($nextLeaveSessionId=='') {
      echo CREATE_NEXT_LEAVE_SESSION;    
      die;  
    }
    
    $conditionCarryForward = " AND lse.leaveSessionId = $leaveSessionId ";
    $carryForwardArray = $carryForwardManager->getEmployeeCarryForwardList($conditionCarryForward,' lse.employeeId, ls.leaveSetId ','',$nextLeaveSessionId);
    
    
    // leave employee
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
       $conditions .= ' AND isActive = 1 ';
    }
    else {
       $conditions .= ' WHERE isActive = 1 ';
    }
 
    $employeeId = 0;   
    for($j=0;$j<count($carryForwardArray);$j++) {
      $employeeId .=",".$carryForwardArray[$j]['employeeId'];   
    }
    
    $conditions .= " AND emp.employeeId IN ($employeeId) ";

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";         

    $totalArray = $employeeManager->getTotalIcardEmployeeList($conditions);
    $employeeRecordArray = $employeeManager->getIcardEmployeeList($conditions,$limit,$orderBy);  
    $cnt = count($employeeRecordArray);
    
    $carryEmployeeId='';
    $tableData='';
    for($i=0;$i<$cnt;$i++) {
       $id = $employeeRecordArray[$i]['employeeId'];
       
       if($carryEmployeeId=='') {
         $carryEmployeeId = $id;
       }
       else {
          $carryEmployeeId .= ",".$id; 
       }
        
       if($employeeRecordArray[$i]['dateOfJoining']=='0000-00-00') {
          $employeeRecordArray[$i]['dateOfJoining'] = NOT_APPLICABLE_STRING;
       }
       else {
          $employeeRecordArray[$i]['dateOfJoining'] = UtilityManager::formatDate($employeeRecordArray[$i]['dateOfJoining']); 
       }
       
       if(strip_slashes($employeeRecordArray[$i]['employeeName']) == '') {
         $employeeRecordArray[$i]['employeeName']  = NOT_APPLICABLE_STRING;
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
       
       $employeeRecordArray[$i]['leaveTypeName']  = NOT_APPLICABLE_STRING;
       $employeeRecordArray[$i]['leaveValue']  = NOT_APPLICABLE_STRING;
       $employeeRecordArray[$i]['taken']  = NOT_APPLICABLE_STRING;
       $employeeRecordArray[$i]['carryForward']  = NOT_APPLICABLE_STRING;
       
       
       $find='';
       $recordFind='';
       for($j=0;$j<count($carryForwardArray);$j++) {
          if($carryForwardArray[$j]['employeeId'] == $id) {
              $recordFind='1';
              $bg = $bg =='trow0' ? 'trow1' : 'trow0';
              $leaveTypeId = $carryForwardArray[$j]['leaveTypeId'];
               
              $tableData .= "<tr class='$bg'>";
              if($find=='') {
                 $tableData .= "<td valign='top' class='padding_top' align='left'>".($records+$i+1)."</td>  
                               <td valign='top' class='padding_top' align='left'>".$employeeRecordArray[$i]['employeeName']."</td>
                               <td valign='top' class='padding_top' align='left'>".$employeeRecordArray[$i]['employeeCode']."</td>
                               <td valign='top' class='padding_top' align='left'>".$employeeRecordArray[$i]['designationName']."</td>
                               <td valign='top' class='padding_top' align='left'>".$employeeRecordArray[$i]['dateOfJoining']."</td>";
                 $find=1;               
              }
              else {
                 $tableData .= "<td valign='top' class='padding_top' align='left' colspan='5'>&nbsp;</td>";   
              }
             
              $allowed = $carryForwardArray[$j]['leaveValue']+$carryForwardArray[$j]['carryForward'];
			     
              $balance = $allowed-$carryForwardArray[$j]['taken'];
              
              if($balance <0) {
                $balance = 0;
              }
              
              $val = $nextLeaveSessionId."~".$id."~".$leaveTypeId."~".$balance;    
              $checkStatus ="";
              if($carryForwardArray[$j]['carryForwardStatus']!=-1) {
                $checkStatus="checked=checked";  
              }
              $checkall = '<input type="checkbox" name="chb[]" '.$checkStatus.' value="'.$val.'">';
              
              $tableData .= "<td valign='top' class='padding_top' align='left'>".$carryForwardArray[$j]['leaveTypeName']."</td>
                              <td valign='top' class='padding_top' align='right'>".$allowed."</td>
                              <td valign='top' class='padding_top' align='right'>".$carryForwardArray[$j]['taken']."</td>
                              <td valign='top' class='padding_top' align='right'>".$balance."</td>
                              <td valign='top' class='padding_top' align='center'>".$checkall."</td>
                             </tr>";
          } 
          else 
          if($carryForwardArray[$j]['employeeId'] != $id && $recordFind=='1') {
            break;
          }    
       }
    }
    
    $sessionHandler->setSessionVariable('nextLeaveSessionId',$nextLeaveSessionId);
    $sessionHandler->setSessionVariable('carryForwardEmployeeId',$carryEmployeeId);  
    
    if($tableData!='') {
       $tableHead .= $tableData."</table>";  
    }
    else {
       $tableHead .= "<tr class='$bg'><td colspan='10' align='center'>No Data Found</td></tr></table>";   
    }
    
    echo $tableHead.'!~~!'.$totalArray[0]['totalRecords'];  

   
?>    