<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Grade Cards
//
//
// Author :&nbsp;&nbsp;Parveen Sharma
// Created on :&nbsp;&nbsp; (26.02.2009)
// Copyright 2008-2000:&nbsp;&nbsp; syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //require_once(BL_PATH . "/UtilityManager.inc.php");
    //UtilityManager:&nbsp;&nbsp;:&nbsp;&nbsp;ifNotLoggedIn(true);
    //UtilityManager:&nbsp;&nbsp;:&nbsp;&nbsp;headerNoCache();
    
    // Top Part
    
   
    
    $contentHead =  '<table width="90%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="attendanceShortCSS">
                      <tr>
                        <td align="center" colspan="2" height="110px">
                           <CollegeLogo>
                        </td>
                      </tr>';
                      
    $contentAddress ='<tr>
                        <td align="center" valign="top width="100%" colspan="2">
                            <b><Heading></b>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="top width="60%" nowrap>
                            To,
                            <div style="padding-left:60px;font-size:13px;width:38%">
                               <ParentsName>,<br>
                               <Address>
                            </div>
                            <br>
                        </td>
                        <td align="right" valign="top" width="40%" nowrap>
                           <table width="100%" cellpadding="0px" cellspacing="0px" border="0" align="right">
                             <tr>
                               <td align="right" valign="top" width="100%" nowrap>
                                  <b>Date:</b>&nbsp;'.UtilityManager::formatDate(date('Y-m-d')).'
                                  <div style="margin-top:5px;"><studentPhoto></div>
                               </td>
                             </tr>
                           </table>
                        </td>       
                      </tr>';
    
    $contentMessage ='<tr>
                        <td align="left" colspan="2">
                           <strong><DEAR></strong>
                        </td>
                      </tr>';
    
    $contents .=  '<tr>
                    <td valign="top" align="left" colspan="2">
                       <table width="100%" border="0" cellpadding="5px" cellspacing="0px" class="attendanceShortCSS">
                         <tr>
                            <td valign="top" width="100%" colspan="2"> 
                               <PrintMessage>
                               <br><br>
                               Kindly find below the information on the attendance 
                            </td>
                         </tr>
                         <tr>
                         <td valign="top" colspan="2"> 
                            <StudentNameId><br><br>';
  
    // Trimester, Subject Code,..... (Loop Parts)
    $contents1 .=  '<table width="100%" border="1" class="attendanceShortTableBoderCSS" cellspacing="1px" cellpadding="1px">';
    $contents2 .=  '<AttendanceMarksDetail>';
    $contents3 .=  '</table><br>
                    We request you to counsel your ward to be more regular in classes so that he/she derives full benefit from the 
                    academic delivery at the university.';
    
     
    // Bottom Part
    $contents4 .=  '      </td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="attendanceShortCSS">';
    $contents5 =  '<tr>
                        <td valign="top"><br><br><br><br>
                            <div style="padding-left:40px">
                                <signatureImage1>
                            </div>
                            <strong><SIGNATURE></strong><br>
                            <br>
                        
                        </td>
                    </tr>';
                    
    $contents6 =  '<tr>
                        <td valign="top" align="left" ><b><NOTES></b></td>
                    </tr>
                    </table>';

                    
    //echo $contentHead.$contentAddress.$contentMessage.$contents.$contents1.$contents2.$contents3.$contents4.$contents5.$contents6;
?>

<?php // $History: studentAttendanceShortTemplate.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/10    Time: 12:02p
//Updated in $/LeapCC/Templates/StudentReports
//format & validation updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/23/10    Time: 5:42p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/11/10    Time: 12:41p
//Updated in $/Leap/Source/Templates/ScStudentReports
//look & feel updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/28/09   Time: 12:25p
//Updated in $/Leap/Source/Templates/ScStudentReports
//print format updated (table)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/18/09   Time: 2:41p
//Created in $/Leap/Source/Templates/ScStudentReports
//initial checkin

?>