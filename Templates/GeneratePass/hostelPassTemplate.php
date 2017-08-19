<?php 
    global $sessionHandler;
    $busPassInstructions = $sessionHandler->getSessionVariable('HOSTEL_PASS_INSTRUCTIONS');
    $busPassInstitueName = $sessionHandler->getSessionVariable('HOSTEL_PASS_INSTITUTE_NAME');
    $busPassFoundAddress = $sessionHandler->getSessionVariable('HOSTEL_PASS_FOUND_ADDRESS');
    $busPassEmail = $sessionHandler->getSessionVariable('HOSTEL_PASS_INSTITUTE_EMAIL');
    
    
    $icardData='';   
    $icardData='<table height="190px" width="640px" cellpadding="0px" cellspacing="20px" border="0px">
                        <tr>
                          <td valign="top" width="320px" height="190px" class="tableborder">
                            <div style="height:195px; width:320px; overflow:hidden;">         
                               <table border="0" cellspacing="0px" cellpadding="1px" height="100%" width="320px" align="center">
                                    <tr>
                                      <td colspan="4" valign="top" width=45% align="center" class="icardHeading">
                                        <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                                            <td valign="middle">&nbsp;<INSTLOGO></td>
                                            <td valign="top" align="center" class="icardHeading" colspan="4">
                                               <table border="0" cellspacing="0px" cellpadding="0px"  >
                                                 <tr>
                                                    <td align="center" style="padding-left:30px">
                                                        <u><b>HOSTEL PASS</u></b>
                                                    </td>
                                                    <td align="right">
                                                        <StudentId> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td colspan="2"><u><b>'.nl2br($busPassInstitueName).'</b></u></td>
                                                 </tr> 
                                               </table>        
                                            </td>
                                        </table>
                                      </td>
                                    </tr>
                                     
                                    <tr>
                                      <td colspan="4" valign="top" width=45% height=2px align="center" class="icardHeading"></td>
                                    </tr>
                                    <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;<StudentRollNoHead></td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><StudentRollNo></td>
                                         <td width="28%"  rowspan="4" align="center" valign="top"><StudentPhoto></td>                                                                      
                                      </tr>
                                      <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;Name</td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><StudentName></td>
                                      </tr>
                                      <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;Branch</td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><Course></td>
                                      </tr>
                                       <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;Hostel Name</td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><HostelName></td>
                                      </tr>
                                      <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;Room No.</td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><RoomNo></td>
                                         <td width="28%" rowspan="2" align="center" valign="top" class="icardNormal"><IMG><BR>Auth. Sig.</td>
                                      </tr>
                                      <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;Receipt No.</td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><RECEIPTNO></td>
                                      </tr>
				       <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading">&nbsp;Check Out</td>
                                         <td valign="top" align="left" width="4%" class="icardHeading">:</td>
                                         <td valign="top" align="left" width="46%" class="icardNormal"><CheckOut></td>
                                      </tr>
                                      <tr>
                                         <td valign="top" align="left" width="22%" class="icardHeading"></td>
                                         <td valign="top" align="left" width="4%" class="icardHeading"></td>
                                         <td valign="top" align="left" width="46%" class="icardNormal">
                                               
                                         </td>
                                         <td width="28%" rowspan="3" align="center" valign="top" style="border:solid #000000 2px;">&nbsp;</td>                                        
                                      </tr>
                                      <tr>
                                         <td align="center" colspan="3" class="icardHeading" style="border-top:solid #000000 2px; vertical-align:middle;"><b>'.nl2br(trim($busPassEmail)).'</b></td>
                                      </tr>                                                                                                
                                </table>
                              </div>                                
                           </td> 
                           
                           <td valign="top" width="320px" height="190px"  class="tableborder">
                            <div style="height:190px; width:320px; overflow:hidden;">         
                               <table border="0" cellspacing="0" cellpadding="0" width="320px" height="100%" align="center">
                                 <tr>
                                    <td valign="top" align="left" width="21%" height="30px" class="icardHeading">&nbsp;Address:</td>
                                    <td valign="top" align="left" width="79%" class="icardNormal"><StudentAddress></td>
                                 </tr>
                                 <tr>
                                    <td valign="top" align="left" width="21%" class="icardHeading">&nbsp;Contact No.:</td>
                                    <td valign="top" align="left" width="79%" class="icardNormal"><StudentContact></td>
                                 </tr>
                                 <tr>
                                  <td colspan="2" valign="top">
                                    <table width="10%" border="0">
                                       <td valign="top" nowrap align="left" width="12%" class="icardHeading">Date of Birth:</td>
                                       <td valign="top" nowrap align="left" width="21%" class="icardNormal"><StudentDOB></td>
                                       <td valign="top" nowrap align="left" width="30%" class="icardHeading" style="padding-left:12px">Blood Group:</td>
                                       <td valign="top" nowrap align="left" width="5%" class="icardNormal"><StudentBloodGroup></td>
                                    </table>   
                                 </tr>
                                 <tr>
                                         <td valign="top" align="left" colspan="2" class="icardHeading">&nbsp;Instructions:</td>
                                     </tr>
                                     <tr>
                                         <td valign="top" align="left" colspan="2" class="icardHeading" style="padding-left:5px">'.nl2br($busPassInstructions).'</td>
                                     </tr>
                                     <tr>
                                         <td valign="top" align="center" colspan="2" class="icardHeading">'.nl2br($busPassFoundAddress).'</td>
                                     </tr>
                                     <tr>
                                         <td valign="top" align="center" colspan="2" class="icardHeading">
                                            <StudentRegNo>
                                         </td>
                                     </tr>
                               </table> 
                      </div>                              
                    </td>
                 </tr>
             </table>';
?>
