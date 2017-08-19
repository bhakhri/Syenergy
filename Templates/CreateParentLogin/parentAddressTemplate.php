<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Parent Login Details
//
// Author :Parveen Sharma
// Created on :(26.02.2009)
// Copyright 2008-2000 Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   
    global $sessionHandler;   
    // Top Part
    $contentHead =  '<table width="800px" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                         <tr>
                             <td align="center" colspan="2" height="110px">
                                 <CollegeLogo>
                             </td>
                         </tr>
                         <tr>
                             <td align="left" valign="top">
                                <div style="padding-left:60px;font-size:13px;width:38%">
                                   <ParentsName>,<br>
                                   <Address>
                                </div>
                                <br>
                             </td>
                             <td align="right" valign="top">
                                <b>Date:</b>&nbsp;'.UtilityManager::formatDate(date('Y-m-d')).'&nbsp;&nbsp;
                             </td>
                         </tr>
                         <tr>
                             <td align="left" valign="top" colspan="2" style="font-size:12px;">
                                Dear Parent,  
                             </td>
                         </tr>
                         <tr>
                           <td align="justify" valign="middle" colspan="2">
                             <table width="780px" align="justify" class="reportData"> 
                                <tr>
                                    <td align="justify" valign="top" style="font-size:12px;">
                                        Team of faculty members and administrative staff at '.$sessionHandler->getSessionVariable('InstituteName').' strive hard to ensure a high quality and 
                                        application oriented learning of your ward.<br>
                                        Keeping in line with the academic philosophy at '.$sessionHandler->getSessionVariable('InstituteName').' :<br>
                                    </td>   
                                </tr>
                                <tr>
                                    <td align="center" valign="top" style="font-size:13px;">
                                        <b>&quot;Foster Creativity in Education<br> 
                                        Use Technology for Promotion&quot; <b><br>
                                    </td>   
                                </tr>
                                <tr>
                                    <td align="justify" valign="top" style="font-size:12px;">
                                        I feel extremely glad in bringing it to your kind notice that, we are going to achieve another 
                                        milestone with the usage of latest of technology in updating you with the academic performance 
                                        of your ward live on internet. This means that, you would be
                                       <ul type="disc">
                                           <li>able to monitor the academic performance of your ward in terms of getting to see his complete marks/grades record
                                           <li>updated with all the happenings in the campus through all e-Notices
                                           <li>updated with the upcoming evaluation components for courses, your ward is perusing in a particular trimester
                                           <li>able to have a close check on the attendance record of your ward for all the courses
                                           <li>be updated on the achievements of your ward or if he/she has been found involved in any kind of in disciplinary activities etc.
                                       </ul>
                                       Besides this, there are many other features, which you can explore yourself on the software application. 
                                       Please follow the below mentioned simple guidelines to access this extremely useful software application:<br>
                                  </td>   
                                </tr>
                             </table>   
                            </td>  
                         </tr>  
                         <tr> 
                             <td align="center" valign="top" colspan="2">
                               <table width="60%" cellpadding="5px" cellspacing="0px" border="1px" class="reportData">
                                 <tr>
                                   <td>
                                         <table width="100%" border="0px" align="center">
                                          <tr>
                                             <td colspan="3" valign="middle" height="5px">
                                                <b><u>Student Details</u><b>
                                             </td>
                                          </tr>
                                          <tr>
                                            <td width="10%"><b>College Roll No.</b></td>
                                            <td width="3%"><b>:</b></td> 
                                            <td width="87%" align="left"><rollno></td>
                                          </tr>
                                          <tr>
                                            <td><b>Student Name</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left"><studentName></td>
                                          </tr>
                                          <tr>
                                            <td><b>Class</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left"><className></td>
                                          </tr>
                                          <tr>
                                             <td colspan="3" valign="middle" height="5px">
                                                <br><b><u><loginDetail></u><b>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td colspan="3" valign="middle" height="5px">
                                                <b><u>STEP 1:</u></b>&nbsp;(Open Internet Explorer or any other Web Browser & type the <b>URL</b> given below)
                                             </td>
                                          </tr>
                                          <tr>
                                            <td width="30%"><b>URL</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left">'.HTTP_PATH.'</td>
                                          </tr>
                                           <tr>
                                             <td colspan="3" valign="middle" height="5px">
                                                <br><b><u>STEP 2:</u></b>&nbsp;Enter Login Detail as given below and Click <b>Submit</b> button
                                             </td>
                                          </tr>
                                          <tr>
                                            <td width="30%"><b>Username</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left"><username></td>
                                          </tr>
                                          <tr>
                                            <td><b>Password</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left"><password></td>
                                          </tr>
                                          <tr>
                                            <td><b>Institute</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left">'.$sessionHandler->getSessionVariable('InstituteAbbr').'</td>
                                          </tr>
                                          <tr>
                                            <td><b>Session</b></td>
                                            <td><b>:</b></td> 
                                            <td align="left">'.$sessionHandler->getSessionVariable('SessionName').'</td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>      
                             </td>
                         </tr>
                         <tr>
                           <td align="justify" valign="top" colspan="2" style="font-size:12px;">
                               I am very sure, you would be able to appreciate the usefulness of this newly added dimension to our academic system. <br><br>  

                               Thanks ! <br><br><br>


                               Yours sincerely, <br><br><br>

                               <authorizedName><br>
                               <designation>
                           </td>
                         </tr>';

    //  echo $contentHead;
?>
<?php // $History: parentAddressTemplate.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/17/09    Time: 11:26a
//Created in $/LeapCC/Templates/CreateParentLogin
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/19/09    Time: 3:36p
//Updated in $/Leap/Source/Templates/CreateParentLogin
//letter text updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/05/09    Time: 3:10p
//Updated in $/Leap/Source/Templates/CreateParentLogin
//parent login details step base format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/05/09    Time: 2:22p
//Updated in $/Leap/Source/Templates/CreateParentLogin
//college logo added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/04/09    Time: 6:03p
//Updated in $/Leap/Source/Templates/CreateParentLogin
//template layout updated (Parent Letter base code updated)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/31/09    Time: 6:38p
//Created in $/Leap/Source/Templates/CreateParentLogin
//initial checkin

?>