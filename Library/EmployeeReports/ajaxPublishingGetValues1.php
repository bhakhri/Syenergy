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
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['publishId'] ) != '') {
   require_once(MODEL_PATH . "/EmployeeManager.inc.php");
   $publishingManager = EmployeeManager::getInstance();
    
   $publisherInfo="";        
    
    global $publisherScopeArr;              
    $publishingArray = $publishingManager->getPublishingList(' AND publishId="'.add_slashes($REQUEST_DATA['publishId']).'"','type');
    
    if(is_array($publishingArray) && count($publishingArray)>0 ) {  
        if($publishingArray[0]['publishOn']=='0000-00-00') {
           $publishingArray[0]['publishOn'] = NOT_APPLICABLE_STRING;
        }
        else {
           $publishingArray[0]['publishOn'] = UtilityManager::formatDate($publishingArray[0]['publishOn']);
        }
        
         if($publishingArray[0]['scopeId']==0 || $publishingArray[0]['scopeId']=="") {
          $publishingArray[0]['scopeId'] = NOT_APPLICABLE_STRING;
        }
        else {
          $publishingArray[0]['scopeId'] = $publisherScopeArr[$publishingArray[0]['scopeId']];      
        }
       
        $type = $publishingArray[0]['type'];
        $publishedBy = $publishingArray[0]['publishedBy'];
        $description = $publishingArray[0]['description'];
        $attachmentFile  = (strip_slashes($publishingArray[0]['attachmentFile'])=='' ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($publishingArray[0]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />');    
        $accpLetter  = (strip_slashes($publishingArray[0]['attachmentAcceptationLetter'])=='' ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($publishingArray[0]['attachmentAcceptationLetter']).'" onclick="download(this.name);" title="Download File" />');    
        
        $publisherInfo .= "<table align='left' width='100%' border='0' class='anyid' cellspacing='1px' cellpadding='3px'>
                              <tr class='row0'> 
                                 <td valign='top' width='27%'><b>Employee Name</b></td>
                                 <td valign='top' width='3%'><b>:</b></td>
                                 <td valign='top' width='68%'>".$publishingArray[0]['employeeName']."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Employee Code</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$publishingArray[0]['employeeCode']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Type</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$type."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Publish On</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$publishingArray[0]['publishOn']."</td>
                              </tr>   
                              <tr class='row0'>  
                                 <td valign='top'><b>Publsished By</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$publishedBy."</td>
                              </tr>   
                              <tr class='row1'>  
                                 <td valign='top'><b>Description</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$description."</td>   
                              </tr>
                              <tr class='row0'>  
                                 <td valign='top'><b>Scope</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$publishingArray[0]['publicationName']."</td>   
                              </tr>
                              <tr class='row1'>  
                                 <td valign='top'><b>Attachment</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$attachmentFile ."</td>   
                              </tr>
                               <tr class='row1'>  
                                 <td valign='top'><b>Acceptation Letter</b></td>
                                 <td valign='top'><b>:</b></td>
                                 <td valign='top'>".$accpLetter."</td>   
                              </tr>
                            </table>"; 
    }
    echo $publisherInfo;
}
else {
    echo "0";   
}
// $History: ajaxPublishingGetValues1.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:04p
//Updated in $/LeapCC/Library/EmployeeReports
//updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/21/09    Time: 12:41p
//Updated in $/LeapCC/Library/EmployeeReports
//new enhancement added "attachmentAcceptationLetter" in Employee
//Publisher tab 
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