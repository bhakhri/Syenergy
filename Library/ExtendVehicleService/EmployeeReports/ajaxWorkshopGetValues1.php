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
    
if(trim($REQUEST_DATA['workshopId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $workshopManager = EmployeeManager::getInstance();
    
    $workshopInfo="";        
    
    $workshopArray = $workshopManager->getWorkshopList(' AND workshopId="'.add_slashes($REQUEST_DATA['workshopId']).'"','employeeName');
    if(is_array($workshopArray) && count($workshopArray)>0 ) {  
       
        if($workshopArray[0]['sponsored']=='N') {
          $workshopArray[0]['sponsoredDetail'] = NOT_APPLICABLE_STRING;
        }
        else {
          $workshopArray[0]['sponsoredDetail'] = $workshopArray[0]['sponsoredDetail'];
        }
        
        if($workshopArray[0]['startDate']=='0000-00-00') {
          $workshopArray[0]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
          $workshopArray[0]['startDate'] = UtilityManager::formatDate($workshopArray[0]['startDate']);
        }
        
        if($workshopArray[0]['endDate']=='0000-00-00') {
          $workshopArray[0]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
          $workshopArray[0]['endDate'] = UtilityManager::formatDate($workshopArray[0]['endDate']);
        }
        
        
        $workshopInfo .= "<table align='left' width='100%' border='0' class='anyid' cellspacing='1px' cellpadding='3px'>
                              <tr class='row0'> 
                                 <td valign='top' width='27%'><b>Employee Name</b></td>
                                 <td valign='top' width='3%'><b>:</b></td>
                                 <td valign='top' width='68%'>".$workshopArray[0]['employeeName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Employee Code</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['employeeCode']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Topic</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['topic']."</td>
                              </tr>   
                               <tr class='row1'>  
                                 <td valign='top'><b>Start Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['startDate']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>End Date</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['endDate']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Sponsored</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['sponsoredDetail']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Location</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['location']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Other Speakers</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['otherSpeakers']."</td>   
                              </tr>
                              <tr class='row0'>  
                                 <td valign='top'><b>Audience</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['audience'] ."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Attendees</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$workshopArray[0]['attendees']."</td>   
                              </tr>
                            </table>"; 
    }
    echo $workshopInfo;
}
else {
    echo "0";   
}
// $History: ajaxWorkshopGetValues1.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:04p
//Updated in $/LeapCC/Library/EmployeeReports
//updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/16/09    Time: 5:16p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin 
//

?>