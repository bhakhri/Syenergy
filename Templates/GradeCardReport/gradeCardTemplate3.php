<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Grade Cards
// Author :&nbsp;&nbsp;Parveen Sharma
// Created on :&nbsp;&nbsp; (26.02.2009)
// Copyright 2008-2000:&nbsp;&nbsp; Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    //require_once(BL_PATH . "/UtilityManager.inc.php");
    //UtilityManager:&nbsp;&nbsp;:&nbsp;&nbsp;ifNotLoggedIn(true);
    //UtilityManager:&nbsp;&nbsp;:&nbsp;&nbsp;headerNoCache();
    
    // Top Part
    
    $notes="<b>Note:&nbsp;</b><i>This is a computer generated report and requires no signatures.</i>";
    $gradeSheet = "<b>CONSOLIDATED GRADE SHEET</b>";   
    
    $contentHead =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                      <tr>
                        <td align="center" colspan="2" height="120px">';
                  //   <CollegeLogo>
    $contentHead .=  '</td>
                      </tr>';
                      
    $contentAddress ='<tr>
                        <td align="left" valign="top">
                            <To>
                            <div style="padding-left:60px;font-size:13px;width:38%">
                               <ParentsName>,<br>
                               <Address>
                            </div>
                            <br>
                        </td>
                        <td align="right" valign="top">
                          <table width="100%" border="0" cellpadding="0px" cellspacing="0px">
                            <tr>
                               <td align="right" valign="top"> 
                                <b>Date:</b>&nbsp;'.UtilityManager::formatDate(date('Y-m-d')).'&nbsp;&nbsp;
                               </td>
                             </tr>
                            </tr>   
                               <td align="right" valign="top"> 
                                <STUDENTPHOTO1>&nbsp;
                               </td>
                            </tr>
                          </table>  
                        </td>
                      </tr>';
    
    $contentMessage ='<tr>
                        <td colspan="2" width="100%">
                          <table width="100%" border="0" cellpadding="0px" cellspacing="0px">
                            <tr>   
                                <td align="right" valign="top" width="15%" colspan="3" style="padding-right:60px"> 
                                <nobr><strong>Sr No:</strong</nobr>
                                </td> 
                            </tr>
                            <tr>
                                <td align="right" valign="top" width="15%">
                                 &nbsp;
                                </td>  
                                <td align="center" valign="top" width="70%">                                
                                    <TRIMESTER>
                                    <div align="center"><GRADESHEET></div>
                                </td>
                                <td align="right" valign="bottom" width="15%">
                                   <studentPhoto>
                                </td>
                            </tr>    
                          </table>
                        </td>     
                      </tr>';
                      
    $contentMessage1 ='<tr>
                        <td align="left" valign="top" width="100%" colspan="2">                                
                           <strong><DEAR></strong><br>
                            <TRIMESTER>
                            <div align="center"><GRADESHEET></div>
                        </td>
                      </tr>';
                                                              
    
    $contents .=  ' <tr>
                        <td valign="top"  colspan="2">
                        <table width="100%" border="0" cellpadding="1px" cellspacing="1px">
                            <tr>
                                <td width="50%" align="left"><nobr><strong>NAME&nbsp;:&nbsp;</strong><Name></nobr></td> 
                                <td width="50%" align="right"><nobr><strong>STUDENT ID&nbsp;:&nbsp;</strong><IdNumber></nobr></td>  
                            </tr>
                            <tr><td height="4px"></td></tr>';
                              
              $contentsF  = '<tr>
                                <td width="7%" colspan="8"><nobr><strong>FATHER\'S NAME&nbsp;:&nbsp;</strong><FatherName></nobr></td>
                            </tr>';
                            
              $contentsP  = '<tr>
                                <td width="50%" align="left"><nobr><strong>FATHER\'S NAME&nbsp;:&nbsp;<FatherName></nobr></td> 
                                <td width="50%" align="right"><nobr><strong>PROGRAMME&nbsp;:&nbsp;</strong></nobr><Programme> (<TimeDuration>)</nobr></td>  
                            </tr>
                        </table>';
  
  
    // Trimester, Course Code,..... (Loop Parts)
    $contents1 .=  '<table width="100%" border="0" cellpadding="2px" cellspacing="0px">
                      <tr><td colspan="3" height="5px"></td></tr>
                      <tr><td></td></tr>
                      <tr><td colspan="3" style="font-size:12px"><b><Trimester><b></td></tr>
                      <tr>
                        <td width="100%" colspan="3" align="left">
                        <table width="100%" border="1" class="reportTableBorder" cellspacing="2px" cellpadding="4px">
                        <tr>
                            <td valign="top" width="12%"><nobr><strong>COURSE CODE</strong></nobr></td>
                            <td valign="top" width="52%"><nobr> <strong>COURSE</strong></nobr></td>
                            <td valign="top" align="right" width="8%"><nobr><strong>CREDITS</strong></nobr></td>
                            <td valign="top" align="center" width="8%"><nobr><strong>GRADE</strong></nobr></td>
                        </tr>';
    
    // Details..... (Loop Parts) Repeat
    $contents2 .=  '<tr>
                     <td valign="top"> <CourseCode> </td>
                     <td valign="top"> <CourseName> </td>
                     <td valign="top" align="right"><div style="padding-right:5px"><Credits></div></td>
                     <td valign="top" align="left" style="padding-left:35px" ><nobr><Grade></nobr></td>
                 </tr>';
                 
    $contents3 .=  '</table>
                    </td>
                    </tr>';
    
    // Points Grade......  (Loop Parts)
    $contents3 .=  '<tr>
                    <td valign="top" width="25%" align="left">
                        <table cellspacing="2px" cellpadding="0px">
                          <tr>
                             <td align="left">
                                <strong><nobr><SHOW_FULL_NAME>&nbsp;GRADE POINT AVERAGE (<SHOW_SHORT_NAME>GPA)
                                &nbsp;:&nbsp;<GradePointAverage></nobr>
                             </td>
                          </tr>
                          <tr><td height="4px"></td></tr>
                          <tr>
                             <td align="right"><strong><nobr>CUMULATIVE GRADE POINT AVERAGE (CGPA)
                             &nbsp;:&nbsp;<CumulativeGradePointAverage></nobr>
                             </td>
                          </tr>
                        </table>     
                        <br>
                    </td>
                    <td valign="top" width="30%">&nbsp;&nbsp;&nbsp;</strong></td>
                    <td valign="top" width="25%" align="right">
                        <table cellspacing="2px" cellpadding="0px">
                          <tr>
                             <td align="right">
                             <strong><nobr><SHOW_FULL_NAME>&nbsp;CREDITS EARNED</nobr>
                             &nbsp;:&nbsp;<CurrentCredits></nobr></td>
                          </tr>
                          <tr><td height="4px"></td></tr>
                          <tr>
                             <td align="left">
                             <strong><nobr>CUMULATIVE CREDITS EARNED
                             &nbsp;:&nbsp;<EarnedCredits></nobr>
                             </td>
                          </tr>
                        </table>     
                    </td>
                    </tr>
                  </table>';
    
    // Bottom Part
    $contents4 .=  '</td>
                    </tr>
                    <tr>
                    </table>
                    <table width="90%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">';
    $contents5 =  '<tr>
                        <td valign="top" colspan="5">
                            <div style="padding-left:&nbsp;&nbsp;40px">
                                <signatureImage1>
                            </div>
                            <strong>Controller of Examinations</strong><br>
                            <br>
                            <strong>Date:&nbsp;&nbsp;</strong>'.date('d-M-Y').'
                        </td>
                    </tr>';
                    
    $contents6 =  '<tr>
                        <td valign="top" align="left" colspan="5"><EXAM_TYPE_NOTE><br><NOTES></td>
                    </tr>
                    </table>';

    $cgpaHead =  '<table width="60%" border="0" cellpadding="5px" cellspacing="0px" align="right" class="reportData">
                    <tr><td align="center"><br><i><b>CGPA Range & No. of Students</b></i></td></tr>
                    <tr>
                    <td width="100%" >
                        <table  width="100%" class="reportTableBorder" border="1" cellpadding="4px" cellspacing="4px" align="right" class="reportData">                   
                        <tr align="center">
                           <th width="5%"> >=9 </th>      
                           <th width="5%"> >=8 & <9 </th>
                           <th width="5%"> >=7 & <8 </th>
                           <th width="5%"> >=6 & <7 </th>
                           <th width="5%"> >=5 & <6 </th>
                           <th width="5%"> >=4 & <5 </th>
                           <th width="5%"> <4 </th>
                        </tr>';
                       
    $cgpaContents = '<tr align="center">
                       <td><G9></td>      
                       <td><G8></td>
                       <td><G7></td>
                       <td><G6></td>
                       <td><G5></td>
                       <td><G4></td>
                       <td><G0></td>
                    </tr>';
  //echo $contents.$contents1.$contents2.$contents3.$contents4;
?>

