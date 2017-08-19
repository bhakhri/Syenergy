<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : 16.07.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','VehicleRouteDetailReport');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleDetailReportsManager.inc.php");
    $vehicleDetailReportsManager = VehicleDetailReportsManager::getInstance();
    
    $fromDate = $REQUEST_DATA['fromDate'];
    
    $vehicleArray = $vehicleDetailReportsManager->getVehicleDetailList($fromDate); 
    $programmeArray = $vehicleDetailReportsManager->getProgrammeList($fromDate);
    $studentArray = $vehicleDetailReportsManager->getVehicleStudentList($fromDate); 
     
    $blank = NOT_APPLICABLE_STRING; 
     
    
    $totalVehicleStudent = array();  
    $finalArray = array();
    for($i=0;$i<count($studentArray);$i++) {
       $batchId = $studentArray[$i]['batchId'];   
       $degreeId = $studentArray[$i]['degreeId'];   
       $branchId = $studentArray[$i]['branchId']; 
       $busRouteId = $studentArray[$i]['busRouteId'];     
       $boys = $studentArray[$i]['totalBoys'];     
       $girls = $studentArray[$i]['totalGirls'];  
       
       if($boys=='') {
         $boys=0;  
       }
       
       if($girls=='') {
         $girls=0;  
       }
          
       $tot = doubleval($boys)+doubleval($girls);
       
       if($tot=='') {
         $tot=0;  
       }
       $finalArray[$batchId][$degreeId][$branchId][$busRouteId]['B']=$boys; 
       $finalArray[$batchId][$degreeId][$branchId][$busRouteId]['G']=$girls;
       $finalArray[$batchId][$degreeId][$branchId][$busRouteId]['T']=$tot;
    }
    
    $tableHead= getTableHead($vehicleArray);
    
    $ttBatchName="";
    $tableData = "";    
    for($i=0; $i<count($programmeArray); $i++) {
      $batchId = $programmeArray[$i]['batchId'];   
      $degreeId = $programmeArray[$i]['degreeId'];   
      $branchId = $programmeArray[$i]['branchId'];  
      
      $batchName = $programmeArray[$i]['batchName'];
      $degreeCode = $programmeArray[$i]['degreeCode'];
      $branchCode = $programmeArray[$i]['branchCode']; 
      
      if($ttBatchName!=$batchName) {
        $ttBatchName = $batchName; 
      }
      else {
        $batchName = "";  
      }
      
	$netTotal ='0';
      $grandTotal='0';
      $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
      $tableData .= "<tr class='$bg'>
                       <td valign='top' class='padding_top' nowrap align='left'><b>".$batchName."</b></td>  
                       <td valign='top' class='padding_top' nowrap align='left'>".$degreeCode." - ".$branchCode."</td>";
      for($j=0;$j<count($vehicleArray);$j++) { 
        $busRouteId = $vehicleArray[$j]['busRouteId'];
        $boys ='';
        $girls ='';
        $total ='';
        $boys  = $finalArray[$batchId][$degreeId][$branchId][$busRouteId]['B'];
        $girls = $finalArray[$batchId][$degreeId][$branchId][$busRouteId]['G'];
        $total = $finalArray[$batchId][$degreeId][$branchId][$busRouteId]['T']; 
        
        if($boys=='0' || $boys=='') {
          $boys='0';  
        }
       
        if($girls=='0'  || $girls=='') {
          $girls='0';  
        }
        
        if($total=='0'  || $total=='') {
          $total='0';  
        }
        
        $totalVehicleStudent[$busRouteId]['B'] = doubleval($totalVehicleStudent[$busRouteId]['B'])+doubleval($boys);
        $totalVehicleStudent[$busRouteId]['G'] = doubleval($totalVehicleStudent[$busRouteId]['G'])+doubleval($girls);
        $totalVehicleStudent[$busRouteId]['T'] = doubleval($totalVehicleStudent[$busRouteId]['T'])+doubleval($total);
        
        if($boys=='0' || $boys=='') {
          $boys=$blank;  
        }
       
        if($girls=='0'  || $girls=='') {
          $girls=$blank;  
        }
        
        if($total=='0'  || $total=='') {
          $total=$blank;  
        }
        
        $tableData .= "<td valign='top' class='padding_top' align='center'>".$boys."</td>  
                       <td valign='top' class='padding_top' align='center'>".$girls."</td>  
                       <td valign='top' class='padding_top' align='center'><b>".$total."</b></td>";
        
        
        if($total=='') {
          $total=0;  
        }               
        $grandTotal += $total;  
	
      }
      $tableData .= "<td valign='top' class='padding_top' align='center'><b>".$grandTotal."</b></td>
                     </tr>";   
	$netTotal += $grandTotal;
    }
   
   
  
   
  
    $totalStudentList ="";
    $capacityList ="";
   $vacantSeat="";
    
    $ttTotal='0';
    $ttCapacity='0';
    $ttVacant='0';
    
    for($j=0;$j<count($vehicleArray);$j++) {
       $busRouteId = $vehicleArray[$j]['busRouteId'];    
       //$hostelType = $vehicleArray[$j]['hostelType'];
       $totalCapacity ='0';
       
       $capBoy ='';
       $capGirl ='';
       $capTotal ='';
       
       $boys ='';
       $girls ='';
       
       $capBoy = $totalVehicleStudent[$busRouteId]['B'];
       $capGirl = $totalVehicleStudent[$busRouteId]['G'];
       $capTotal = $totalVehicleStudent[$busRouteId]['T'];
       
       if($capBoy=='0' || $capBoy=='') {
         $capBoy='0';  
       }
       
       if($capGirl=='0'  || $capGirl=='') {
         $capGirl='0';  
       }
        
       if($capTotal=='0'  || $capTotal=='') {
         $capTotal='0';  
       }
       
       $colspan='';
       if($hostelType=='1') { // Girls  
         $girls = $totalCapacity; 
       }
       if($hostelType=='2') { // Boys  
         $boys = $totalCapacity;
       }
       if($hostelType=='3') { // Mixed  
         $mixed = $totalCapacity; 
         $colspan="colspan='2'";
       }
       
       if($boys=='0' || $boys=='') {
         $boys='0';  
       }
       
       if($girls=='0'  || $girls=='') {
         $girls='0';  
       }
        
       
       
       
       $vacTotal = (doubleval($boys)+doubleval($girls))-doubleval($capTotal);
       
       
        if($boys=='0' || $boys=='') {
          $boys=$blank;  
        }
       
        if($girls=='0'  || $girls=='') {
          $girls=$blank;  
        }
        
        if($mixed=='0'  || $mixed=='') {
          $mixed=$blank;  
        }
        
        if($totalCapacity=='0'  || $totalCapacity=='') {
          $totalCapacity=$capTotal;  
        }
        
             $ttTotal=doubleval($ttTotal)+doubleval($capTotal);
        
        if($capBoy=='0' || $capBoy=='') {
          $capBoy = $blank;
        }
       
        if($capGirl=='0'  || $capGirl=='') {
          $capGirl = $blank;
        }
        
        if($capTotal=='0'  || $capTotal=='') {
          $capTotal = $blank;
        }
	
       
        if($colspan=='') {
          $capacityList .= "<td valign='top'  class='padding_top' nowrap align='center'><b>$capBoy</b></td>
                                <td valign='top'  class='padding_top' nowrap align='center'><b>$capGirl</b></td>
                                <td valign='top'  class='padding_top' nowrap align='center'><b>$capTotal</b></td>";                                                      
         
                                                            
        }
       
   
    }   
    
    $bg = $bg =='trow0' ? 'trow1' : 'trow0';
    $tableData .= "<tr class='$bg'>
                       <td valign='top' colspan='2'class='padding_top' nowrap align='left'>
                        <b>Total Students</b></td>".$capacityList."
                       <td valign='top'  class='padding_top' nowrap align='center'><b>$ttTotal</b></td>    
                   </tr>";

   

    
   
    echo $tableHead.$tableData;
die;    
    
    

    function getTableHead($vehicleArray) {
       
       $result  = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                     <tr class='rowheading'>
                        <td width='2%' rowspan='2' valign='middle' class='searchhead_text' ><b>Batch</b></td>     
                        <td width='2%' rowspan='2' valign='middle' class='searchhead_text' ><b>Programme</b></td>";     
     
       if(count($vehicleArray) >0) {
           $tdGender='';                 
           for($i=0;$i<count($vehicleArray);$i++) {
              $routeName = $vehicleArray[$i]['routeName'];
              $result .= "<td width='2%' colspan='3' valign='middle' align='center' class='searchhead_text'><b>$routeName</b></td>";     
             
              $tdGender .="<td width='2%' valign='middle' align='center' class='searchhead_text'><b>Boys</b></td>
                           <td width='2%' valign='middle' align='center' class='searchhead_text'><b>Girls</b></td>
                           <td width='2%' valign='middle' align='center' class='searchhead_text'><b>Total</b></td>";     
           }             
           $result .= "<td width='2%' rowspan='2' valign='middle' align='center'  class='searchhead_text'><b>Total</b></td>
                       </tr>";  
           
           $result .= "<tr class='rowheading'>$tdGender</tr>"; 
       }
       
       return $result;
    }
 
 ?>
