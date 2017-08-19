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
define('MODULE','EmployeeInformation');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);   
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['consultId'] ) != '') {
   require_once(MODEL_PATH . "/EmployeeManager.inc.php");
   $consultManager = EmployeeManager::getInstance();
    
   $consultingInfo="";        
    
            
    $consultingArray = $consultManager->getConsultingList(' AND consultId="'.add_slashes($REQUEST_DATA['consultId']).'"','employeeName');
    if(is_array($consultingArray) && count($consultingArray)>0 ) {  
        
        if($consultingArray[0]['startDate']=='0000-00-00') {
           $consultingArray[0]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $consultingArray[0]['startDate'] = UtilityManager::formatDate($consultingArray[0]['startDate']);
        }
        
        if($consultingArray[0]['endDate']=='0000-00-00') {
           $consultingArray[0]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $consultingArray[0]['endDate'] = UtilityManager::formatDate($consultingArray[0]['endDate']);
        }
       
        if( $consultRecordArray[0]['amountFunding']=='') {
           $consultRecordArray[0]['amountFunding'] = 0;              
        }
              
        
        $consultingInfo .= "<table align='left' width='100%' border='0' class='anyid' cellspacing='1px' cellpadding='3px'>
                              <tr class='row0'> 
                                 <td valign='top' width='27%'><b>Employee Name</b></td>
                                 <td valign='top' width='3%'><b>:</b></td>
                                 <td valign='top' width='68%'>".$consultingArray[0]['employeeName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Employee Code</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['employeeCode']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Project Name</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['projectName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Sponsor Name</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['sponsorName']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Start Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['startDate']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>End Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['endDate']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Amount Funding</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['amountFunding'] ."</td>   
                              </tr>
                              <tr class='row0'>  
                                 <td valign='top'><b>Remarks</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$consultingArray[0]['remarks'] ."</td>   
                              </tr>
                            </table>"; 
    }
    echo $consultingInfo;
}
else {
    echo "0";   
}
// $History: ajaxConsultingGetValues1.php $
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