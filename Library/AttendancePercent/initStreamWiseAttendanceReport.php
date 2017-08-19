<?php
//--------------------------------------------------------
//This file returns the array of of Percentage Wise attendance report
// Author :Aditi Miglani
// Created on : 8-Nov-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);

    require_once(MODEL_PATH . "/StreamWiseAttendanceManager.inc.php");
    $streamWiseAttendanceManager = StreamWiseAttendanceManager::getInstance();
    
    global $sessionHandler;

    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    global $sessionHandler;

    $sessionHandler->setSessionVariable('IdToStreamStudentHeader','');    
    $sessionHandler->setSessionVariable('IdToStreamStudentData','');


    $valueArrayHead = array();	
    $valueArrayData = array();

    $labelId = $REQUEST_DATA['labelId'];
    $fromDate = $REQUEST_DATA['fromDate'];  
    $toDate = $REQUEST_DATA['toDate'];  
    
   
    $dateCondition=" AND (att.fromDate BETWEEN '$fromDate' AND '$toDate') AND att.attendanceType = '2' ";

    $condition = " AND tt.timeTableLabelId = '$labelId'";
    $degreeBranchArray = $streamWiseAttendanceManager->getTimeTableDegreeBrach($condition);
    
    $condition = " AND tt.timeTableLabelId = '$labelId'";
    $studyPeriodArray = $streamWiseAttendanceManager->getTimeTableStudyPeriod($condition);
    
    
    $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'>
                        <td width='2%'  valign='middle' class='searchhead_text' ><b>#</b></td>
                        <td width='5%'  valign='middle' class='searchhead_text' align='left'><strong>From Date</strong></td>
                        <td width='5%'  valign='middle' class='searchhead_text' align='left'><strong>To Date</strong></td>
                        <td width='10%' valign='middle' class='searchhead_text' align='left'><strong>Degree</strong></td>
                        <td width='10%' valign='middle' class='searchhead_text' align='left'><strong>Branch</strong></td>";

    $valueArrayHead[0]['headLabel']='#';
    $valueArrayHead[0]['headName']='srNo';

    $valueArrayHead[1]['headLabel']='From Date';
    $valueArrayHead[1]['headName']='fromDate';

    $valueArrayHead[2]['headLabel']='To Date';
    $valueArrayHead[2]['headName']='toDate';

    $valueArrayHead[3]['headLabel']='Degree';
    $valueArrayHead[3]['headName']='degreeCode';

    $valueArrayHead[4]['headLabel']='Branch';
    $valueArrayHead[4]['headName']='branchCode';

    for($i=0;$i<count($studyPeriodArray);$i++) {
      $periodName = $studyPeriodArray[$i]['periodName'];

      $valueArrayHead[5+$i]['headLabel']=$periodName." "."%";
      $valueArrayHead[5+$i]['headName']='study_'.$i;
      $tableData .= "<td width='10%' valign='middle' class='searchhead_text' align='left'><strong>$periodName %</strong></td>";  
    }                    
    $tableData .= "<td width='10%' valign='middle' class='searchhead_text' align='left'><strong>Over All %</strong></td>"; 
    
    $valueArrayHead[5+$i]['headLabel']='Over All %';                                                                       
    $valueArrayHead[5+$i]['headName']='overAllPer';
    $tableData .= "</tr>";
  
   

    $fromDate = UtilityManager::formatDate($fromDate);
    $toDate = UtilityManager::formatDate($toDate);
   
    for($i=0;$i<count($degreeBranchArray);$i++) {
       $branch = $degreeBranchArray[$i]['branchCode']; 
       $degree = $degreeBranchArray[$i]['degreeCode'];
       
       $branchId = $degreeBranchArray[$i]['branchId']; 
       $degreeId = $degreeBranchArray[$i]['degreeId'];
       
       $valueArrayData[$i]['srNo']=($i+1);
       $valueArrayData[$i]['fromDate']=$fromDate;
       $valueArrayData[$i]['toDate']=$toDate;
       $valueArrayData[$i]['degreeCode']=$degree;
       $valueArrayData[$i]['branchCode']=$branch;
       $valueArrayData[$i]['overAllPer']=NOT_APPLICABLE_STRING; 

       $overAllPer=0;
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $tableData .= "<tr class='$bg'>
                       <td width='2%'  valign='middle' class='padding_top' >".($i+1)."</td>
                       <td width='5%'  valign='middle' class='padding_top' align='left'>".$fromDate."</td>
                       <td width='5%'  valign='middle' class='padding_top' align='left'>".$toDate."</td>
                       <td width='10%' valign='middle' class='padding_top' align='left'>".$degree."</td>
                       <td width='10%' valign='middle' class='padding_top' align='left'>".$branch."</td>";
       
       $countFinalStudyPeriodId ='0';                 
       for($j=0;$j<count($studyPeriodArray);$j++) { 
          $studyPeriodId = $studyPeriodArray[$j]['studyPeriodId'];
          
          $condition = " AND tt.timeTableLabelId = '$labelId' AND c.branchId = '$branchId' 
                         AND c.degreeId = '$degreeId' AND studyPeriodId = '$studyPeriodId' "; 
          $attendanceArray = $streamWiseAttendanceManager->getAttendance($condition,$dateCondition);   
          
          
          $attended ='0';
          $delivered='0';
          for($k=0;$k<count($attendanceArray);$k++) {
             $attended += $attendanceArray[$k]['attended'];
             $delivered += $attendanceArray[$k]['delivered'];
          }
          if($delivered=='0') {
            $percentage = NOT_APPLICABLE_STRING;  
          }
          else {
            $percentage = $attended/$delivered*100; 
            $percentage =number_format($percentage ,2);    
            $countFinalStudyPeriodId = $countFinalStudyPeriodId + 1; 
          }
          $show = $attended.'/'.$delivered;
	      if($percentage=='') {
            $percentage = NOT_APPLICABLE_STRING;  
	      }
          $tableData .= "<td width='10%' valign='middle' class='padding_top' align='left'>$percentage</td>";    
          
	      $id="study_".$j;
	      $valueArrayData[$i][$id]=$percentage;
          $overAllPer+=$percentage;
       }
       if($countFinalStudyPeriodId != '0') {
         $overPercentage = number_format(($overAllPer/($countFinalStudyPeriodId*100)*100),2);
       }
       else {
         $overPercentage = 0;
       }
       $valueArrayData[$i]['overAllPer']=$overPercentage;           
       $tableData .= "<td width='10%' valign='middle' class='padding_top' align='left'>$overPercentage</td>";     
       $tableData .= "</tr>";               
    }
    
    if(count($degreeBranchArray)<=0) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $tableData .= "<tr class='$bg'>
			<td class='padding_top' colspan='50' align='center' >
			  <nobr>No Data Found</nobr>
			</td>
		    </tr>";     
    }
    $sessionHandler->setSessionVariable('IdToStreamStudentHeader',$valueArrayHead);    
    $sessionHandler->setSessionVariable('IdToStreamStudentData',$valueArrayData);

    echo   $tableData;
                        
?>
