<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);   
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['seminarId'] ) != '') {
   require_once(MODEL_PATH . "/EmployeeManager.inc.php");
   $seminarManager = EmployeeManager::getInstance();
    
   $seminarInfo="";        
   
   global $seminarParticipationArr;     
            
    $seminarRecordArray = $seminarManager->getSeminarsList(' AND seminarId="'.add_slashes($REQUEST_DATA['seminarId']).'"','topic');
    if(is_array($seminarRecordArray) && count($seminarRecordArray)>0 ) {  
       if($seminarRecordArray[0]['startDate']=='0000-00-00') {
           $seminarRecordArray[0]['startDate'] = NOT_APPLICABLE_STRING;
       }
       else {
           $seminarRecordArray[0]['startDate'] = UtilityManager::formatDate($seminarRecordArray[0]['startDate']);
       }
        
       if($seminarRecordArray[0]['endDate']=='0000-00-00') {
           $seminarRecordArray[0]['endDate'] = NOT_APPLICABLE_STRING;
       }
       else {
           $seminarRecordArray[0]['endDate'] = UtilityManager::formatDate($seminarRecordArray[0]['endDate']);
       }

        if($seminarRecordArray[0]['participationId']==0 || $seminarRecordArray[0]['participationId']=="") {
           $part = NOT_APPLICABLE_STRING;
        }
        else {
           $part = $seminarParticipationArr[$seminarRecordArray[0]['participationId']];      
        }
        
        if($seminarRecordArray[0]['fee']=='') {
           $seminarRecordArray[0]['fee'] = NOT_APPLICABLE_STRING;
        }

        $seminarInfo .= "<table align='left' width='100%' border='0' class='anyid' cellspacing='1px' cellpadding='3px'>
                              <tr class='row0'> 
                                 <td valign='top' width='27%'><b>Employee Name</b></td>
                                 <td valign='top' width='3%'><b>:</b></td>
                                 <td valign='top' width='68%'>".$seminarRecordArray[0]['employeeName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Employee Code</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['employeeCode']."</td>
                              </tr>  
                              <tr class='row0'>  
                                 <td valign='top'><b>Orgnaised By</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['organisedBy']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Topic</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['topic']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Description</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['description']."</td>
                              </tr>                               
                              <tr class='row1'>  
                                 <td valign='top'><b>Start Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['startDate']."</td>   
                              </tr>
                              <tr class='row0'>  
                                 <td valign='top'><b>End Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['endDate']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Seminar Place</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['seminarPlace']."</td>   
                              </tr>
                              <tr class='row0'>  
                                 <td valign='top'><b>Seminar Fee</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$seminarRecordArray[0]['fee']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Participation</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$part."</td>   
                              </tr>
                            </table>"; 
    }
    echo $seminarInfo;
}
else {
    echo "0";   
}
// $History: ajaxSeminarGetValues1.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 5:26p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//role permission, alignment, new enhancements added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/16/09    Time: 5:40p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//update files teacher login 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/16/09    Time: 5:16p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin 
//

?>