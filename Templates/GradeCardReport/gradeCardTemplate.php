<style type="text/css">
    #tbl td{border:1px solid #666666}
</style>
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
    
    $notes="<b>Note:&nbsp;</b><i>This is a computer generated report and requires no signatures.</i>";
    $gradeSheet = "<b>CONSOLIDATED GRADE SHEET</b>";   
    
    $contentHead =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                      <tr>
                        <td align="center" colspan="2" height="0px">
                          <table width="100%" border="0" cellpadding="0px" cellspacing="0px" height="130px">
                            <tr>
                                <td align="left" valign="top" width="15%">
                                  <INSTITUTELOGO>
                                </td>  
                                <td align="center" valign="top" width="85%" style="font-size:30px;">                                
                                    <b><INSTITUTENAME></b>
                                </td>
                            </tr>    
                          </table>
                        </td>
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
                                <td align="center" valign="top" width="100%" >                 
                                  <table width="100%" border="0" cellpadding="0px" cellspacing="0px">
                                    <tr><td height="15px"></td></tr>
                                    <tr>
                                      <td align="center" valign="top"> <span style="font-size:22px;color:#cc0000"><b>GRADE REPORT</b></span></td>
                                    </tr>
                                    <tr><td height="10px"></td></tr>
                                    <tr>
                                      <td align="center" valign="top"> <span style="font-size:15px;"><b><TRIMESTER></b></span></td>
                                    </tr>
                                    <tr><td height="15px"></td></tr>
                                    <tr>
                                      <td align="center" valign="top"> <span style="font-size:15px;"><div align="center"><GRADESHEET></div> </span></td>
                                    </tr>       
                                  </table>  
                                </td>
                            </tr>    
                          </table>
                        </td>     
                      </tr>
                      <tr>
                        <td align="right" colspan="2" height="0px">
                          <table width="12%" border="0" cellpadding="0px" cellspacing="0px" align="right">
                            <tr>
                                <td align="left" valign="top" width="15%" style="padding-right:10px">
                                  <b>Reg. No.</b>
                                </td>  
                            </tr>    
                           <tr>
                                <td align="left" valign="top" width="15%" style="padding-right:10px">
                                  <nobr><REGNO></nobr>
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
                        <td valign="top"  colspan="2">';
                              
              $contentsF  = '';
                            
              $contentsP  = '';
  
  
    // Trimester, Course Code,..... (Loop Parts)
    $contents1 .=  '<table width="100%" border="0" cellpadding="2px" cellspacing="0px">
                      <tr><td colspan="3" height="5px"></td></tr>
					  <tr><td></td></tr>
                      <tr>
                        <td colspan="3">
                          <table width="90%" border="0" cellpadding="1px" cellspacing="1px">
                            <tr>
                                <td width="5%"><nobr><strong>Name</strong></nobr></td>
                                <td width="2%"><nobr><strong>:</strong></nobr></td>  
                                <td width="50%"><nobr><Name></nobr></td> 
                                <td width="5%"><nobr><strong>Semester</strong></nobr></td>  
                                <td width="2%"><nobr><strong>:</strong></nobr></td>  
                                <td width="41%"><nobr><SEMESTER></nobr></td>  
                            </tr>
                            <tr><td height="4px"></td></tr>
                            <tr>
                                <td width="5%"><nobr><strong>Roll No.</strong></nobr></td>
                                <td width="2%"><nobr><strong>:</strong></nobr></td>  
                                <td width="50%"><nobr><ROLLNO></nobr></td> 
                                <td width="5%"><nobr><strong>Session</strong></nobr></td>  
                                <td width="2%"><nobr><strong>:</strong></nobr></td>  
                                <td width="41%"><nobr><SESSION></nobr></td>
                            </tr>
                            <tr><td height="4px"></td></tr>
                          </table>  
                        </td>
                      </tr>
                      <tr>
                        <td width="100%" colspan="3" align="left">
                        <table width="100%" border="1" class="reportTableBorder" cellspacing="2px" cellpadding="4px">
                        <tr>
                            <td valign="top" width="12%"><nobr><strong>COURSE CODE</strong></nobr></td>
                            <td valign="top" width="52%"><nobr> <strong>TITLE OF THE COURSE</strong></nobr></td>
                            <td valign="top" align="right"  width="8%"><nobr><strong>CREDIT</strong></nobr></td>
                            <td valign="top" align="center" width="8%"><nobr><strong>GRADE</strong></nobr></td>
                            <td valign="top" align="right"  width="8%"><nobr><strong>GRADE POINT</strong></nobr></td> 
                            <td valign="top" align="right"  width="8%"><nobr><strong>TOTAL GRADE POINT</strong></nobr></td> 
                        </tr>';
                        
    
    $contentsCourse = '<table width="100%" cellpadding="2px" cellspacing="0px">
                        <tr>
                          <td width="100%" colspan="3" align="left">
                            <table width="100%" border="0" id="tbl" class="reportTableBorder" cellspacing="2px" cellpadding="4px">
                              <tr>
                                <td valign="top" width="12%"><nobr><strong>CODE</strong></nobr></td>
                                <td valign="top" width="52%"><nobr> <strong>SUBJECT NAME</strong></nobr></td>
                                <td valign="top" align="right"  width="8%"><nobr><strong>CREDITS</strong></nobr></td>
                                <td valign="top" align="center" width="8%"><nobr><strong>GRADE</strong></nobr></td>
                                <td valign="top" align="right"  width="8%"><nobr><strong>GRADE POINT</strong></nobr></td> 
                                <td valign="top" align="right"  width="8%"><nobr><strong>TOTAL GRADE POINT</strong></nobr></td> 
                              </tr>';                    
    
    // Details..... (Loop Parts) Repeat
    $contents2 .=  '<tr>
                     <td valign="top"> <CourseCode> </td>
                     <td valign="top"> <CourseName> </td>
                     <td valign="top" align="right"><div style="padding-right:5px"><Credits></div></td>
                     <td valign="top" align="center" ><nobr><Grade></nobr></td>
                     <td valign="top" align="right" ><nobr><GradePoint></nobr></td> 
                     <td valign="top" align="right" ><nobr><TotalGradePoint></nobr></td> 
                 </tr>';
    
    $contents2a .=  '<CREDITSTOTAL>';
    $contents3 .=  '</table>
                    </td>
                    </tr>';
    
    // Points Grade......  (Loop Parts)
  /*  $contents3 .=  '<tr>
                    <td valign="top" colspan="3" width="25%" align="right">
                        <table cellspacing="2px" cellpadding="0px">
                          <tr><td height="50px"></td></tr>  
                          <tr>
                             <td style="font-weight: bold;font-family: Arial, Helvetica, sans-serif; font-size: 16px;"><nobr>GRADE POINT AVERAGE (GPA)</nobr></td>
                             <td style="font-weight: bold;font-family: Arial, Helvetica, sans-serif; font-size: 16px;"><nobr>:&nbsp;</nobr></td>
                             <td style="padding-right:5px;font-weight: bold;font-family: Arial, Helvetica, sans-serif; font-size: 16px;"><nobr><GradePointAverage></nobr></td>
                          </tr>
                         </table>'; */
   
   
    $onlyGPA =  '</table>
                <br><br><br>
                 <table border="0" class="reportTableBorder" align="right" cellspacing="0px" cellpadding="0px" width="40%">
                    <tr>
                       <td width="50%" style="padding-left:5px">&nbsp;</td>
                       <td width="50%" nowrap align="right" style="font-weight: bold;font-family: Arial, Helvetica, sans-serif; font-size: 16px;"><B>GRADE POINT AVERAGE (GPA):</b>&nbsp;<b><SHOW_GPA></b></td>
                    </tr>
                 </table>
                 <br><br><br><br>';
   
    $contents3 .=  '<tr>
                    <td valign="top" width="25%" align="left">
                        <table cellspacing="2px" cellpadding="0px">
                          <SHOW_GPA>
                          <SHOW_CGPA>
                        </table>     
                        <br>
                    </td>
                    <td valign="top" width="30%">&nbsp;&nbsp;&nbsp;</strong></td>
                    <td valign="top" width="25%" align="right">
                        <table cellspacing="2px" cellpadding="0px">
                          <SHOW_CREDITS_EARNED>
                          <SHOW_CUMULATIVE_CREDITS_EARNED>
                        </table>     
                    </td>
                    </tr>
                  </table>';
    
    // Bottom Part
    $contents4 .=  '</td>
                    </tr>
                    <tr>
                    </table>
                    <table width="90%" border="1" cellpadding="5px" cellspacing="0px" align="center" class="reportData">';
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
                        <td valign="top" <ALIGN_PERSON> colspan="10" style="font-family: Arial, Helvetica, sans-serif; font-size:13px;">
                            <b><AUTHORIZEDNAME></b><br>
                            <b><DESIGNATION></b>
                            <EXAM_TYPE_NOTE><br><NOTES>
                        </td>
                    </tr>
                    </table>';
                    
    $contentAlign =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" class="reportData">
                          <tr>
                            <td valign="top" <ALIGN_PERSON>  style="font-family: Arial, Helvetica, sans-serif; font-size:13px;">
                                <b><AUTHORIZEDNAME></b><br>
                                <b><DESIGNATION></b>
                                <EXAM_TYPE_NOTE><br><NOTES>
                            </td>
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

<?php // $History: scGradeCardTemplate.php $
//
//*****************  Version 24  *****************
//User: Parveen      Date: 4/07/10    Time: 2:30p
//Updated in $/Leap/Source/Templates/ScGradeCard
//new format code updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 1/21/10    Time: 11:09a
//Updated in $/Leap/Source/Templates/ScGradeCard
//format update
//
//*****************  Version 22  *****************
//User: Parveen      Date: 1/12/10    Time: 2:34p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format & validation  updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 1/12/10    Time: 1:34p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 1/12/10    Time: 1:30p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format updated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 7/08/09    Time: 3:40p
//Updated in $/Leap/Source/Templates/ScGradeCard
//display & formating updated (Pagewise trimester show) 
//
//*****************  Version 18  *****************
//User: Parveen      Date: 7/08/09    Time: 2:36p
//Updated in $/Leap/Source/Templates/ScGradeCard
//printing page setting and format updated
//
//*****************  Version 17  *****************
//User: Parveen      Date: 7/08/09    Time: 1:24p
//Updated in $/Leap/Source/Templates/ScGradeCard
//query & functions updated (Display in two trimester Data in One Page)
//
//*****************  Version 16  *****************
//User: Parveen      Date: 7/07/09    Time: 6:54p
//Updated in $/Leap/Source/Templates/ScGradeCard
//formating, alingnment & condition updated
//
//*****************  Version 15  *****************
//User: Parveen      Date: 6/03/09    Time: 4:51p
//Updated in $/Leap/Source/Templates/ScGradeCard
//alignment format update
//
//*****************  Version 14  *****************
//User: Parveen      Date: 6/03/09    Time: 2:06p
//Updated in $/Leap/Source/Templates/ScGradeCard
//date add
//
//*****************  Version 13  *****************
//User: Parveen      Date: 6/02/09    Time: 3:02p
//Updated in $/Leap/Source/Templates/ScGradeCard
//Institue Logo Remove 
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/02/09    Time: 10:51a
//Updated in $/Leap/Source/Templates/ScGradeCard
//Updated grade print report format  & alignment settings 
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/01/09    Time: 6:37p
//Updated in $/Leap/Source/Templates/ScGradeCard
//Updated grade print report format 
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 6/01/09    Time: 6:03p
//Updated in $/Leap/Source/Templates/ScGradeCard
//Updated grade print report format
//
//*****************  Version 9  *****************
//User: Parveen      Date: 5/30/09    Time: 5:21p
//Updated in $/Leap/Source/Templates/ScGradeCard
//template layout changes
//
//*****************  Version 8  *****************
//User: Parveen      Date: 5/25/09    Time: 6:13p
//Updated in $/Leap/Source/Templates/ScGradeCard
//note message update
//
//*****************  Version 7  *****************
//User: Parveen      Date: 5/25/09    Time: 10:33a
//Updated in $/Leap/Source/Templates/ScGradeCard
//grade card template Trimesters update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 5/22/09    Time: 1:27p
//Updated in $/Leap/Source/Templates/ScGradeCard
//template format update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 3/09/09    Time: 1:05p
//Updated in $/Leap/Source/Templates/ScGradeCard
//code update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 3/09/09    Time: 11:30a
//Updated in $/Leap/Source/Templates/ScGradeCard
//code update
//
//*****************  Version 2  *****************
//User: Parveen      Date:&nbsp;&nbsp; 2/26/09    Time: 4:37p
//Updated in $/Leap/Source/Templates/GradeCard
//comments added
//
//

?>
