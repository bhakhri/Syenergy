<?php

  $contentMain1 =  '<table width="800px" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                     <tr>
                        <td width="100%">';

  $contentHead =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                      <tr>
                         <td align="right" style="padding-right:10px;font-size:16px;"><b>S.No...................</b></td>
                      </tr>
                      <tr>
                        <td align="center" width="100%">
                          <table width="100%" border="0" cellpadding="0px" cellspacing="0px" height="130px" class="reportData">     
                            <tr>
                                <td align="left" valign="top" width="15%">
                                  <UniversityLogo>
                                </td>  
                                <td align="center" valign="top" width="85%" style="font-size:20px;">                                
                                    <b><UniversityName></b>
                                    <hr >
                                    <span style="font-size:15px;"><b><UniversityAddress></b></span> 
                                </td>
                            </tr>    
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" width="100%">  
                          <table width="100%" border="0" cellpadding="0px" cellspacing="0px" class="reportData">
                            <tr>
                              <td align="center" valign="top"> <span style="font-size:24px;"><b>Grade Card</b></span></td>
                            </tr>
                            <tr><td height="10px"></td></tr>
                            <tr>
                              <td align="center" valign="top">
                                <span style="font-size:15px;"><b><InstituteName></b></span>
                              </td>
                            </tr>
                            <tr><td height="10px"></td></tr>  
                            <tr>
                              <td align="center" valign="top">
                                <span style="font-size:15px;">
                                    <b><DegreeName></b>
                                </span>    
                                <span style="font-size:13px;"><b><ShowDateTime></b></span>
                              </td>
                            </tr>
                            <tr><td height="15px"></td></tr>
                            <tr>
                              <td align="center" valign="top">
                                <span style="font-size:15px;"><b><BranchName></b></span>
                              </td>
                            </tr>  
                            <tr><td height="15px"></td></tr>        
                          </table>  
                          </td>        
                      </tr>
                   </table>';

  $contentNoHeader =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                          <tr>
                            <td align="center" width="100%">  
                              <table width="100%" border="0" cellpadding="0px" cellspacing="0px" class="reportData">
                                <tr><td height="200px">&nbsp;</td></tr>  
                                <tr>
                                  <td align="center" valign="top">
                                    <span style="font-size:15px;">
                                        <b><DegreeName></b>
                                    </span>    
                                    <span style="font-size:13px;"><b><ShowDateTime></b></span>
                                  </td>
                                </tr>
                                <tr><td height="15px"></td></tr>
                                <tr>
                                  <td align="center" valign="top">
                                    <span style="font-size:15px;"><b><BranchName></b></span>
                                  </td>
                                </tr>  
                                <tr><td height="15px"></td></tr>        
                              </table>  
                              </td>        
                          </tr>
                       </table>';                 
                   
  $contentStudent ='<table width="90%" border="0" align="center" cellpadding="1px" cellspacing="1px" class="reportData">     
                        <tr><td height="4px"></td></tr>
                        <tr>
                           <td width="50%"><nobr><strong>Name&nbsp;:&nbsp;</strong>&nbsp;<STUDENTNAME></nobr></td>
                           <td width="50%" align="right"><nobr><strong><RollNoAbbr>&nbsp;:&nbsp;</strong>&nbsp;<ROLLNO></nobr></td>  
                        </tr>
                        <tr><td height="4px"></td></tr>
                        <tr>
                           <td width="5%"><nobr><strong>Father\'s Name&nbsp;:&nbsp;</strong>&nbsp;<FATHERNAME></nobr></td>
                        </tr>
                        <tr><td height="4px"></td></tr>
                        <tr>
                           <td width="5%" colspan="10"><nobr><strong>Institute&nbsp;:&nbsp;</strong>&nbsp;<instituteName></nobr></td>
                        </tr>
                    </table>';

  $contentsCourse1 = '<br><br><table width="100%" border="0" class="reportData" cellspacing="0px" cellpadding="0px">
                        <tr>
                           <td valign="top" align="center" width="15%" style="font-size:15px;">
                                <i><nobr><strong>DETAILS OF CREDIT & GRADE</strong></nobr></i>
                           </td>     
                        </tr>
                        <tr><td height="10px"></td></tr>
                        <tr>
                           <td width="100%" align="center" >     
                      <table width="95%" border="1" class="reportData" cellspacing="0px" cellpadding="4px" style="font-size:12px;">
                      <tr>
                        <td valign="top" align="center" width="15%"><nobr><strong>Course Code</strong></nobr></td>
                        <td valign="top" align="center" width="55%"><nobr> <strong>Course Title</strong></nobr></td>
                        <td valign="top" align="center" align="right"  width="10%"><nobr><strong>Credit</strong></nobr></td>
                        <td valign="top" align="center" align="center" width="10%"><nobr><strong>Grade</strong></nobr></td>
                      </tr>';                     
                      
  $contentsCourse2 = '<tr>
                        <td valign="top" style="padding-left:5px"> <CourseCode> </td>
                        <td valign="top"style="padding-left:5px"> <CourseName> </td>
                        <td valign="top" align="left"><div style="padding-left:25px"><Credits></div></td>
                        <td valign="top" align="left"><div style="padding-left:30px"><Grade></div></td>
                      </tr>';
   
  $contentsCourse3 = '</table>
                          </td>
                        </tr>
                      </table>';
  
  
  $contentResult ='<table width="92%" border="0" align="center" cellpadding="1px" cellspacing="1px" class="reportData">
                        <tr><td height="12px" colspan="2" width="100%"></td></tr>
                        <tr>
                           <td width="68%" align="left"><nobr><strong>Current Credits&nbsp;:&nbsp;</strong></nobr><CURRENTCREDIT></td>
                           <td width="32%" align="left"><nobr><strong>Current Grade Points&nbsp;:&nbsp;</strong><CURRENTGRADEPOINT></nobr></td>  
                        </tr>
                        <tr><td height="8px"></td></tr>
                        <tr>
                           <td width="5%" colspan="10">
                              <nobr>
                              <strong>Semester Grade Point Average&nbsp;:&nbsp;</strong><GRADEPOINT>&nbsp;(10 Point Scale)
                              </nobr>
                           </td>
                        </tr>
                        <tr><td height="8px" colspan="2" width="100%"></td></tr>
                        <tr>
                           <td align="left"><nobr><strong>Previous Credits&nbsp;:&nbsp;</strong></nobr><PreviousCredit></nobr></td> 
                           <td align="left"><nobr><strong>Previous Points&nbsp;:&nbsp;</strong></nobr><PreviousPoint></nobr></td>  
                        </tr>
                        <tr><td height="8px" colspan="2" width="100%"></td></tr>
                        <tr>
                           <td align="left"><nobr><strong>Less Credits&nbsp;:&nbsp;</strong><LessCredit></nobr></td> 
                         <td align="left" nowrap><nobr><strong>Less Grade Points&nbsp;:&nbsp;</strong></nobr><LessGrade>&nbsp;(for Repeating)</nobr></td>  
                        </tr>
                        <tr>
                           <td align="left"><nobr><strong>Total Credits&nbsp;:&nbsp;</strong></nobr><TotalCredit></nobr></td> 
                           <td align="left"><nobr><strong>Total Grade Points&nbsp;:&nbsp;</strong></nobr><TotalGrade></nobr></td>  
                        </tr>
                        <tr><td height="8px" colspan="2" width="100%"></td></tr>
                        <tr>
                           <td width="5%" colspan="10">
                              <nobr>
                                <strong>Cumulative Grade Point Average&nbsp;:&nbsp;</strong><CumulativeGrade>&nbsp;(10 Point Scale)
                              </nobr>
                           </td>
                        </tr>
                        <tr><td height="8px"></td></tr> 
                    </table>';

                             
    $contentsApprove ='<br><br><table width="90%" border="0" cellpadding="0px" cellspacing="0px" align="center" class="reportData">
                         <tr>
                           <td width="85%" valign="bottom"></td>
                           <td width="15%" valign="bottom" align="center"  nowrap valign="top"><nobr><strong><Authorized></strong></nobr></td>
                         </tr>
                         <tr>
                           <td valign="top">
                            <strong><UnivCityName></strong><br>
                            <strong>Dated&nbsp;:&nbsp;</strong><GradeDated>
                           </td>
                           <td align="center" nowrap height="30px" valign="top"><nobr><strong><Designation></strong></nobr></td>
                         </tr>
                       </table>'; 
                       
    $contentMain2 = "</td></tr></table>";                                              
