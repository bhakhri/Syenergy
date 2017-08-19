<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim(add_slashes($REQUEST_DATA['mdpId'])) != '') {
   require_once(MODEL_PATH . "/EmployeeManager.inc.php");
   $empManager = EmployeeManager::getInstance();
    
   $mdpInfo="";        
   
    global $mdpSelectedArr;     
            
    $mdpRecordArray = $empManager->getMdpList(' AND mdpId="'.add_slashes($REQUEST_DATA['mdpId']).'"','mdpName');
    if(is_array($mdpRecordArray) && count($mdpRecordArray)>0 ) {  
       if($mdpRecordArray[0]['startDate']=='0000-00-00') {
           $mdpRecordArray[0]['startDate'] = NOT_APPLICABLE_STRING;
       }
       else {
           $mdpRecordArray[0]['startDate'] = UtilityManager::formatDate($mdpRecordArray[0]['startDate']);
       }
        
       if($mdpRecordArray[0]['endDate']=='0000-00-00') {
           $mdpRecordArray[0]['endDate'] = NOT_APPLICABLE_STRING;
       }
       else {
           $mdpRecordArray[0]['endDate'] = UtilityManager::formatDate($mdpRecordArray[0]['endDate']);
       }

     $mdpTypeArray = array(1=>'ICTP', 2=>'EDP', 3=>'FDP', 4=>'Seminar', 5=>'Workshop', 6=>'PDP');
	 $mdpSelectArray = array(0=>'Attended' , 1=>'Conducted');
     $cnt = count($mdpRecordArray);
     for($i=0;$i<$cnt;$i++) {
	   $mdpType = $mdpRecordArray[$i]['mdpType'];
	   
	   $mdpArray = explode(',', $mdpType);
	   $str = '';
	   foreach ($mdpArray as $rec) {
		   if (!empty($str)) {
			   $str .= ',';
		   }
		   $str .= $mdpTypeArray[$rec];
	   }
	   $mdpRecordArray[$i]['mdpType'] = $str;
	   
	   if($mdpRecordArray[$i]['mdpType'] == '') {
           $mdpRecordArray[$i]['mdpType'] = NOT_APPLICABLE_STRING;
       }
	    
	   if($mdpRecordArray[$i]['mdp'] == '') {
           $mdpRecordArray[$i]['mdp'] = NOT_APPLICABLE_STRING;
       }
 
       $mdpSelect = $mdpRecordArray[$i]['mdp'];
	   $mdpArray2 = explode(',',$mdpSelect);
       $str2 = '';
	   foreach ($mdpArray2 as $rec2) {
		   if (!empty($str2)) {
			   $str2 .= ',';
		   }
		   $str2 .= $mdpSelectArray[$rec2];
	   }
	   $mdpRecordArray[$i]['mdp'] = $str2;
	   
	} 
        if($mdpRecordArray[0]['sessionsAttended']=='' || $mdpRecordArray[0]['sessionsAttended']=='0') {
           $mdpRecordArray[0]['sessionsAttended'] = NOT_APPLICABLE_STRING;
        }

        $mdpInfo .= "<table align='left' width='100%' border='0' class='anyid' cellspacing='1px' cellpadding='3px'>
                              <tr class='row0'> 
                                 <td valign='top' width='27%'><b>Employee Name</b></td>
                                 <td valign='top' width='3%'><b>:</b></td>
                                 <td valign='top' width='68%'>".$mdpRecordArray[0]['employeeName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Employee Code</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['employeeCode']."</td>
                              </tr>  
                              <tr class='row0'>  
                                 <td valign='top'><b>Mdp Name</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['mdpName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Start Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['startDate']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>End Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['endDate']."</td>
                              </tr>                               
                              
                              <tr class='row0'>  
                                 <td valign='top'><b>Session </b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['sessionsAttended']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Hours</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['hoursAttended']."</td>   
                              </tr>
                              <tr class='row0'>  
                                 <td valign='top'><b>Venue</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['venue']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Description</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['description']."</td>   
                              </tr>
							  <tr class='row0'>  
                                 <td valign='top'><b>Mdp Type</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['mdpType']."</td>   
                              </tr>
							    <tr class='row1'>  
                                 <td valign='top'><b>Mdp </b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$mdpRecordArray[0]['mdp']."</td>   
                              </tr>
                            </table>"; 

}
    echo $mdpInfo;
}
else {
    echo "0";   
}
?>