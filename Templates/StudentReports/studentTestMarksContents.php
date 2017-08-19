<?php 
//-------------------------------------------------------
//  This File outputs test time period Form
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="studentAttendanceForm" action="" method="post" onSubmit="return false;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
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
                                  <table width="65%" border="0" align="left" cellspacing="2px" cellpadding="2px" >
                                    <tr>
                                        <td class="contenttab_internal_rows"><nobr><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>
                                          <nobr><select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:170px" onChange="getLabelClass(); return false;">
                                                    <option value="">Select</option> 
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelDate('','');?>
                                          </select></nobr>
                                       </td>    
                                       <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Degree&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows" colspan="2"><nobr><strong>
<!-- <select size="1" class="htmlElement" name="degreeId" id="degreeId" style="width:260px;" onchange="hideResults(); getSubjectClasses(); ">  -->
<select size="1" class="htmlElement" name="degreeId" id="degreeId" style="width:250px;" onchange="getSubjectClasses(); ">
                                       <option value="">Select</option>      
                                    </select></nobr>
                                       </td>
                                        <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Test Type</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows" colspan="3"><nobr><strong>
                                            <select size="1" class="htmlElement" name="testTypeCategoryId" id="testTypeCategoryId" style="width:255px;">
                                            <option value="">All</option> 
                                            <?php 
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getTestTypeCategory();
                                            ?>
                                            </select></nobr>
                                        </td>
                                     </tr>  
                                     <tr>                                            
                                        <td class="contenttab_internal_rows"><nobr><strong>Subject</strong><nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>
                                        <select size="1" class="htmlElement" style="width:170px;" name="subjectId" id="subjectId" onchange="getSubjectGroups(); return false;" >
                                        <option value="">Select</option>      
                                        </select>
                                        </td>
                                        <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Group</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>
                                            <select name="groupId"  class="htmlElement" id="groupId" style="width:140px;">
                                                <option value="">Select</option>      
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="contenttab_internal_rows" id='sortingFormat1' align="right"><nobr><strong>&nbsp;Sort</strong></nobr></td>
                                        <td class="contenttab_internal_rows" id='sortingFormat2' align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                        <td class="contenttab_internal_rows" id='sortingFormat3' align="left" colspan="5">
                                          <nobr>
                                            <table width="100%" cellspacing="0px" cellpadding="0px" border="0" align="left">      
                                             <tr>
                                                 <td class="contenttab_internal_rows" align="left"><nobr>
                                                    <select size="1" class="inputbox1" name="sortField1" id="sortField1" style="width:105px">
                                                        <option value="universityRollNo">Univ. Roll No.</option>
                                                        <option value="rollNo">Roll No.</option>
                                                        <option value='studentName'>Namewise</option> 
                                                    </select>
                                                    </nobr>
                                                  </td>  
                                                  <td class="contenttab_internal_rows" align="left"><nobr>  
                                                     <table width='100%'>
                                                       <tr>
                                                          <td class="contenttab_internal_rows" align="left"><nobr><strong>Order</strong></nobr></td>
                                                          <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                          <td class="contenttab_internal_rows" align="left"><nobr>  
                                                            <input type="radio" name="sortOrderBy1" id="sortOrderBy1" value="ASC"  checked="checked" onclick="hideResults();" />Asc&nbsp;
                                                            <input type="radio" name="sortOrderBy1" id="sortOrderBy2" value="DESC" onclick="hideResults();" />Desc
                                                            </nobr>
                                                          </td>
                                                        </tr>
                                                     </table>    
                                                   </nobr> 
                                                </td>
                                                <td class="contenttab_internal_rows" style="padding-left:20px"><nobr><strong>
                                                    <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form); return false;" />
                                                </td>
                                             </tr> 
                                            </table>
                                          </nobr>
                                        </td> 
                                    </tr> 
                                    <tr id='showSubjectEmployeeList' style='display:none;'>  
                                          <td class="contenttab_internal_rows" align="left" colspan="20">
                                              <table width="100%">
                                                <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" >
                                                    <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                                       <Label id='idSubjects'>Show Subject & Teacher Details</label></b></a>
                                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                  </td>
                                                 </tr> 
                                                 <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
                                                    <nobr><span id='subjectTeacherInfo'></span></nobr>
                                                  </td>
                                                 </tr> 
                                              </table>
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
                                            <td colspan="1" nowrap width="30%" class="content_title">Student Test Wise Marks Details : </td>
                                          
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
