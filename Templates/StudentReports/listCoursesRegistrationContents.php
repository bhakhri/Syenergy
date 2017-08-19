<?php 
//-------------------------------------------------------
//  This File outputs test time period Form
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="allDetailsForm" action="" method="post" onSubmit="return false;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp;Student Courses Registration Report</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">  
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                  <table width="70%" border="0" align="left" cellspacing="2px" cellpadding="2px" >
                                  <tr>
                                        <td class="contenttab_internal_rows"  valign="top" ><nobr><strong>&nbsp;Class&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                        <td class="contenttab_internal_rows"  valign="top" ><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows"  colspan="5" valign="top" ><nobr><strong>
                                            <select size="1" class="htmlElement" name="classId" id="classId" onchange="getTerm(); return false;"style="width:270px;">
                                            <option value="">Select</option>     
                                            <?php                                                                 
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getRegistrationClassList() ;   
                                            ?> 
                                            </select></nobr>
                                        </td>   
                                        <td class="contenttab_internal_rows" valign="top" style="padding-left:20px;" rowspan="3"><nobr><strong>&nbsp;Subject&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows" valign="top" rowspan="3"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows" valign="top" rowspan="3"><nobr><nobr>
                                            <select multiple name='subjectId[]' id='subjectId' size='5' class='htmlElement2' style='width:240px'>
                                            <?php  
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getCourseList() ;   
                                            ?> 
                                            </select><br>
                                             Select&nbsp;&nbsp;
                                             <a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','allDetailsForm');">All</a> / 
                                             <a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','allDetailsForm');">None</a>
                                            </nobr>
                                        </td>
                                        <td class="" style="padding-left:20px" rowspan="3" valign="bottom" align='left'><nobr><strong>
                                           <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateForm(this.form); return false;" />
                                        </td>
                                    </tr>
                                    <tr>
                                       <td class="contenttab_internal_rows"   valign="top" ><nobr><strong>&nbsp;Term&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                       <td class="contenttab_internal_rows"  valign="top" ><nobr><strong>:&nbsp;</strong></nobr></td>
                                       <td class="contenttab_internal_rows"  colspan="5" valign="top" ><nobr><strong>
                                          <table>
                                            <tr>
                                              <td class="contenttab_internal_rows"><nobr>
                                                <select size="1" class="htmlElement" name="termClassId" id="termClassId" style="width:170px;">
                                                <option value="">Select</option>     
                                                <?php  
                                                    //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                    //echo HtmlFunctions::getInstance()->getDegreeList() ;   
                                                ?> 
                                                </select></nobr>
                                              </td>
                                              <td><nobr>  
                                                <input type="checkbox" id="incAll" name="incAll" value="1" >&nbsp;<b>Include All Students</b>
                                                </nobr>
                                              </td>
                                            </tr>   
                                          </table>  
                                        </td>
                                    </tr>
                                    <tr>     
                                        <td class="contenttab_internal_rows"   valign="top" id='sortingFormat1' align="right"><nobr><strong>&nbsp;Sort</strong></nobr></td>
                                        <td class="contenttab_internal_rows"   valign="top" id='sortingFormat2' align="left" ><nobr><b>:&nbsp;</nobr></b></td>  
                                        <td class="contenttab_internal_rows"   valign="top" id='sortingFormat3' align="left"><nobr>
                                            <select size="1" class="inputbox1" name="sortField1" id="sortField1" style="width:105px">
                                                <option value="cgpa">CGPA</option>
                                                <option value='registrationDate'>Date</option>
                                                <option value='confirmId'>Confirm</option> 
                                                <option value="universityRollNo">Univ. Roll No.</option>
                                                <option value="rollNo">Roll No.</option>
                                                <option value='studentName'>Namewise</option> 
                                                <!--<option value='date1'>Date + CGPA</option>
                                                <option value='cgpa1'>CGPA + DATE</option>
                                                <option value='date2'>Confirm + Date + CGPA</option>
                                                <option value='cgpa2'>Confirm + CGPA + DATE</option> -->
                                            </select>
                                            </nobr>
                                          </td>  
                                          <td class="contenttab_internal_rows"  valign="top" align="left"><nobr><strong>&nbsp;&nbsp;Order</strong></nobr></td>
                                          <td class="contenttab_internal_rows"  valign="top" ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                          <td class="contenttab_internal_rows"  valign="top" align="left"><nobr>  
                                              <input type="radio" name="sortOrderBy1" id="sortOrderBy1" value="ASC"  onclick="hideResults();" />Asc&nbsp;</nobr>
                                          </td> 
                                          <td class="contenttab_internal_rows"  valign="top" align="left"><nobr>       
                                              <input type="radio" name="sortOrderBy1" id="sortOrderBy2" value="DESC" checked="checked" onclick="hideResults();" />Desc&nbsp;</nobr>
                                         </td> 
                                   </tr>
                               </table>
                             </td>
                           </tr>      
                        </table>    
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                                        <tr>                                                                       
                                            <td colspan="1" nowrap width="30%" class="content_title">Student Details: </td>
                                            <td colspan="1" nowrap class="content_title" align="right"  width="25%" >
                                                <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print_form.gif" onClick="printForm()" />&nbsp;
                                                <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td valign='top'  colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1050px; height:520px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr id='pageRow' style='display:none;'>    
                                <td valign='top' colspan='1'  class=''>
                                  <table width="98%" valign='top' border="0" class='' cellspacing="0" cellpadding="0" >
                                   <tr>
                                     <td valign='top' colspan='1'  class='' align='left'>    
                                        <span id = 'pagingDiv1' class='contenttab_row1' align='left'></span>
                                     </td>
                                     <td valign='top' colspan='1'  class='' align='right'>   
                                        <span id = 'pagingDiv' align='right'></span> 
                                     </td>
                                   </tr>
                                  </table>      
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                               <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->  
                           
                    </td>
                </tr>
            </table>
        </table>
</form>
<?php 
//$History: studentTestMarksContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/08/10    Time: 11:21a
//Updated in $/LeapCC/Templates/StudentReports
//pagination added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/06/10    Time: 5:13p
//Updated in $/LeapCC/Templates/StudentReports
//option subject condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/05/10    Time: 9:53a
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/02/10    Time: 5:45p
//Updated in $/LeapCC/Templates/StudentReports
//replace test category to test type (update)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/01/10    Time: 3:45p
//Updated in $/LeapCC/Templates/StudentReports
//heading name updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/01/10    Time: 2:33p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
//*****************  Version 14  *****************
//User: Parveen      Date: 3/24/10    Time: 3:53p
//Updated in $/LeapCC/Templates/StudentReports
//condition format updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 3/22/10    Time: 2:22p
//Updated in $/LeapCC/Templates/StudentReports
//time table Label Id base check updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 2/16/10    Time: 10:42a
//Updated in $/LeapCC/Templates/StudentReports
//name updated (Show All Subject Marks)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 2/15/10    Time: 5:43p
//Updated in $/LeapCC/Templates/StudentReports
//added check box (Include All Students) and validation format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/26/09   Time: 5:06p
//Updated in $/LeapCC/Templates/StudentReports
//Duty Leave Base format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 11/16/09   Time: 3:20p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/13/09   Time: 9:54a
//Updated in $/LeapCC/Templates/StudentReports
//format updated all subjects view 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/06/09   Time: 5:50p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/06/09   Time: 10:37a
//Updated in $/LeapCC/Templates/StudentReports
//new column added report type base conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/14/09   Time: 12:14p
//Updated in $/LeapCC/Templates/StudentReports
//CSV & Query Format updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/13/09   Time: 4:55p
//Updated in $/LeapCC/Templates/StudentReports
//link button updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/13/09   Time: 2:44p
//Updated in $/LeapCC/Templates/StudentReports
//consolidated & details report print
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Templates/StudentReports
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/08/08   Time: 11:48a
//Created in $/LeapCC/Templates/StudentReports
//student percentagewise report file added
//


?>
