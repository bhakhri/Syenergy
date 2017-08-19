<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Transcript 
// Author :&nbsp;&nbsp;Parveen Sharma
// Created on :&nbsp;&nbsp; (26.02.2009)
// Copyright 2008-2000:&nbsp;&nbsp; Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------



    // Top Part
    $notes="<b>Note:&nbsp;</b><i>This is a computer generated report and requires no signatures.</i>";
    
    $content =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                      <tr>
                        <td align="center" colspan="2" height="0px">
                          <table width="100%" border="0" cellpadding="0px" cellspacing="0px" height="130px">
                            <tr>
                                <td align="left" valign="top" width="15%">
                                   <INSTITUTELOGO>
                                </td>  
                                <td align="center" valign="top" width="85%">                                
                                    <table width="100%" border="0px" cellpadding="0px" cellspacing="0px" align="right">
                                       <tr>
                                         <td width="80%" nowrap style="font-size:15px;"> 
                                         </td>
                                         <td width="2%" nowrap style="font-size:15px;"> 
                                            <B><SrNoLabel></B>
                                         </td>
                                         <td width="18%" class="tdBorder">
                                           <SrNo>
                                         </td>
                                       </tr>
                                       <tr>
                                         <td align="center" valign="top" colspan="3" >
                                           <span style="font-size:30px;"><b><INSTITUTENAME></b></span>
                                         </td>
                                       </tr>
                                    </table>
                                </td>
                            </tr>    
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td align="right" valign="top" style="padding-right:3%" >
                           <table width="20%" border="0" cellpadding="2px" cellspacing="0px" align="right">   
                             <tr>
                               <td align="left" valign="top" style="width:30%">
                                  <table class="reportTableBorder" width="98%" border="0" cellpadding="0px" cellspacing="0px" align="left">
                                   <tr>
                                     <td nowrap width="2%">
                                        <B>Issue Date:&nbsp;</B>
                                     </td>
                                     <td width="98%" class="tdBorder">
                                       <b><IssueDate></b>
                                    </td>
                                   </tr>
                                  </table>  
                               </td>
                             </tr>
                           </table>    
                         </td>
                       </tr>
                       <tr>
                         <td>     
                           <table width="98%" border="0" cellpadding="4px" cellspacing="0px" align="center" >
                            <tr>
                               <td align="left" valign="top" style="width:50%;">
                                 <table class="reportTableBorder" width="98%" border="0" cellpadding="0px" cellspacing="0px" align="left">
                                   <tr>
                                     <td nowrap width="2%">
                                        <B>NAME&nbsp;</B>
                                     </td>
                                     <td width="98%" class="tdBorder">
                                       <b><StudentName></b>
                                    </td>
                                   </tr>
                                  </table>   
                               </td>     
                               <td align="left" valign="top" style="width:50%">
                                 <table width="98%" border="0" cellpadding="0px" cellspacing="0px" align="left">
                                   <tr>
                                     <td width="2%" nowrap> 
                                        <B>FATHER\'S NAME&nbsp;</B>
                                     </td>
                                     <td width="98%" class="tdBorder">
                                       <FatherName>
                                     </td>
                                   </tr>
                                  </table>  
                               </td>
                            </tr>
                            <tr>
                               <td align="left" valign="top" style="width:10%">
                                  <table class="reportTableBorder" width="98%" border="0" cellpadding="0px" cellspacing="0px" align="left">
                                   <tr>
                                     <td nowrap width="2%">
                                        <B>ROLL NO&nbsp;</B>
                                     </td>
                                     <td width="98%" class="tdBorder">   
                                        <RollNo>
                                     </td>
                                     </tr>
                                  </table>   
                               </td>   
                               <td align="left" valign="top" style="width:10%">
                                   <table class="reportTableBorder" width="98%" border="0" cellpadding="0px" cellspacing="0px" align="left">
                                     <tr>
                                       <td nowrap width="2%">
                                          <B>REG. NO&nbsp;</B>
                                            </td>
                                            <td width="98%" class="tdBorder"> 
                                               <RegNo>
                                             </td>
                                   </tr>
                                   </table>
                              </td>
                            </tr>
                            <tr>
                               <td align="left" valign="top" style="width:10%" colspan="2">
                                 <table class="reportTableBorder" width="99%" border="0" cellpadding="0px" cellspacing="0px" align="left">
                                     <tr>
                                       <td nowrap width="2%">
                                         <B>PROGRAMME OF STUDY&nbsp;</B>
                                       </td>
                                       <td width="98%" class="tdBorder">    
                                          <ProgramName>
                                       </td>
                                     </tr>
                                  </table>        
                               </td>
                            </tr>
                          </table>  
                        </td>
                      </tr>
                      <tr>
                         <td>     
                           <table width="100%" border="1" class="reportTableBorder" cellspacing="2px" cellpadding="4px">
                              <tr>
                                 <td valign="top" align="left" width="12%"><nobr><strong></strong></nobr></td>
                                 <td valign="top" align="left" width="20%"><nobr> <strong>Course Code</strong></nobr></td>
                                 <td valign="top" align="left" width="36%"><nobr><strong>Course Name</strong></nobr></td>
                                 <td valign="top" align="left" width="12%"><nobr><strong>Credits</strong></nobr></td>
                                 <td valign="top" align="left" width="12%"><nobr><strong>Grade</strong></nobr></td> 
                              </tr>
                              <CourseDetail>
                           </table>
                         </td>
                      </tr>      
                  </table>';
   
   $contentFooter = '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                       <tr>  
                          <td nowrap width="50%">
                           
                          </td>
                          <td nowrap width="50%">
                            
                          </td>
                       </tr>
                     </table>'; 
?>
