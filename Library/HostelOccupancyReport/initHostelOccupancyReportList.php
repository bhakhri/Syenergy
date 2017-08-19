<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel room in array from the database, pagination and search, delete 
// functionality
// Author : Jaineesh
// Created on : 16.07.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HostelOccupancyReport');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HostelOccupancyReportManager.inc.php");
    $hostelOccupancyReportManager = HostelOccupancyReportManager::getInstance();
    
    $fromDate = $REQUEST_DATA['fromDate'];
    $vacantArray = $hostelOccupancyReportManager->getVacantRoomList($fromDate); 
   	$room='0';
	 $capacity='0';
	 $stayingTotal='0';
	 $vacantTotal='0';
    $k=0; 
    $finalArray = array();
    for($i=0;$i<count($vacantArray);$i++) {
       $hostelId = $vacantArray[$i]['hostelId']; 
       $roomName = $vacantArray[$i]['roomName'];
       $vacant =  $vacantArray[$i]['vacant'];
       $find='0';
       for($j=0;$j<count($finalArray);$j++) {
          if($finalArray[$j]['hostelId'] == $hostelId) {
            $find='1';
            break;  
         }
       }
       if($find=='0') {
         $finalArray[$k]['hostelId'] = $vacantArray[$i]['hostelId']; 
         $finalArray[$k]['hostelName'] = $vacantArray[$i]['hostelName'];
         $finalArray[$k]['roomTotal'] = $vacantArray[$i]['roomTotal'];
         $finalArray[$k]['totalCapacity'] = $vacantArray[$i]['totalCapacity'];
         $finalArray[$k]['staying'] = $vacantArray[$i]['totalCapacity'] - $vacantArray[$i]['vacant']; 
         $finalArray[$k]['vacant'] = $vacantArray[$i]['vacant'];
         $finalArray[$k]['roomVacant'] = "$roomName($vacant)";
	
         $k++;
       }
       else {
         $finalArray[$j]['staying'] = $finalArray[$j]['staying'] - $vacantArray[$i]['vacant']; 
         $finalArray[$j]['vacant'] = $finalArray[$j]['vacant'] + $vacantArray[$i]['vacant'];  
         $finalArray[$j]['roomVacant'] .= ", $roomName($vacant)";

	
       }
    }
    
    $tableHead= getTableHead();
     
    echo $tableHead;
  
die;
    
    function getTableHead() {
       
       global $finalArray; 
       
       $result  = "<table width='100%' border='0px' cellspacing='2px' cellpadding='2px'>
                    <tr class='rowheading'>
                        <td width='20%' valign='middle' class='searchhead_text' align='left'><b>Hostel</b></td>     
                        <td width='10%' valign='middle' class='searchhead_text' align='center' ><b>Rooms</b></td>
			            <td width='18%' valign='middle' class='searchhead_text' align='center' ><b>No.Of Beds</b></td>
			            <td width='10%' valign='middle' class='searchhead_text' align='center' ><b>Staying</b></td>
			            <td width='10%' valign='middle' class='searchhead_text' align='center' ><b>Vacant</b></td>
			            <td width='30%' valign='middle' class='searchhead_text' align='left' ><b>Vacancies in Room No.</b></td>
                    </tr>";  
      
        for($i=0;$i<count($finalArray);$i++) {   
            $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
           
            $result .= "<tr class='$bg'>
                            <td width='20%' valign='top' class='padding_top' nowrap align='left'>".$finalArray[$i]['hostelName']."</td>  
                            <td width='10%' valign='top' class='padding_top' nowrap align='center'>".$finalArray[$i]['roomTotal']."</td>
                            <td width='10%' valign='top' class='padding_top' nowrap align='center'>".$finalArray[$i]['totalCapacity']."</td>
                            <td width='10%' valign='top' class='padding_top' nowrap align='center'>".$finalArray[$i]['staying']."</td>  
                            <td width='10%' valign='top' class='padding_top' nowrap align='center'>".$finalArray[$i]['vacant']."</td>  
                            <td width='20%' valign='top' class='padding_top' nowrap align='left'>".chunk_split($finalArray[$i]['roomVacant'],95,'<br>')."</td> "; 

		$room += $finalArray[$i]['roomTotal'] ;
		 $capacity+=$finalArray[$i]['totalCapacity'] ;
		 $stayingTotal+=$finalArray[$i]['staying'] ;
		 $vacantTotal+=$finalArray[$i]['vacant'] ;             
        }

	$bg = $bg =='trow0' ? 'trow1' : 'trow0';
	$result .= "<tr class='$bg'>
                             <td width='20%' valign='middle' class='padding_top' align='left'><b>Total</b></td>     
                        <td width='10%' valign='middle' class='padding_top' align='center' ><b>".$room."</b></td>
			            <td width='18%' valign='middle' class='padding_top' align='center' ><b>".$capacity."</b></td>
			            <td width='10%' valign='middle' class='padding_top' align='center' ><b>".$stayingTotal."</b></td>
			            <td width='10%' valign='middle' class='padding_top' align='center' ><b>".$vacantTotal."</b></td>

			            <td width='30%' valign='middle' class='padding_top' align='left' ><b></b></td>";              
                         
        return $result;
    }
 
 ?>
