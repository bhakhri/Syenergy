<?php 
//-------------------------------------------------------
//  This File contains Student grade card template
//
//
// Author :Parveen Sharma
// Created on : 06-03-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>

<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><?php require_once(TEMPLATES_PATH."/breadCrumb.php");?></td>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="middle" colspan="2"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content" align="center">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                      <table align="center" border="0" width="70%" cellspacing="0px" cellpadding="0px"   >
                                            <tr>
                                                <td align="left" valign="top" >
                                                <table border="0" cellspacing="0px" cellpadding="2px"  >
                                                 <!--  
                                                 <tr>
                                                    <td align="left" valign="top">    
                                                       <nobr><strong>Time Table<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" valign="top" ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" valign="top" ><nobr>
                                                    <select size="1" class="htmlElement2" name="timeTableLabelId" style="width:160px" id="timeTableLabelId" onChange="hideDetails2(); getTimeTableBatch();">
                                                        <option value="">Select</option>
                                                        <?php 
                                                            //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           // echo HtmlFunctions::getInstance()->getTimeTableLabelData();                                     
                                                        ?>
                                                      </select></nobr>
                                                    </td>
                                                 </tr>
                                                -->
                                                <tr>
                                                    <td align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Batch<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <select size="1" class="inputbox" name="batchId" id="batchId" style="width:250px" onChange="getDegreeData(); hideDetails1(); return false;">
                                                        <option value="">Select</option>
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->getBatches();
                                                        ?>
                                                    </select></nobr>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Degree<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <select size="1" class="inputbox" name="degreeId" id="degreeId" style="width:250px" onChange="getBranchData(); hideDetails();">
                                                        <option value="">Select</option>
                                                    </select>
                                                    </nobr>
                                                </tr>  
                                                 <tr>
                                                    <td  align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Branch<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <select size="1" class="inputbox" name="branchId" id="branchId" style="width:250px" onChange="getTrimesterData(); hideDetails();">
                                                        <option value="">Select</option>
                                                    </select>
                                                    </nobr>
                                                </tr> 
                                                <tr>
                                                    <td align="left" valign="top">    
                                                       <nobr><strong>Roll No.</strong></nobr>
                                                    </td>   
                                                    <td align="left" valign="top" ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" valign="top" ><nobr>    
                                                    <input type="text" name="rollno" id="rollno" class="inputbox" maxlength="50" style="width:185px" />
                                                    </nobr><br><br><div id='alertUnactive' style="color:red;"></div>
                                                    </td>
                                                 </tr>
                                                </table> 
                                             </td>
                                             
                                                <td colspan="1" valign="top" align="left" class="padding">    
                                                <nobr>
                                                &nbsp;<strong>Semester<?php echo REQUIRED_FIELD ?> :<br>
                         &nbsp;<select multiple name='semesterId[]' id='semesterId' size='5' class="inputbox" style='width:150px'> 
                                                    <?php 
                                                        //  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                         // echo HtmlFunctions::getInstance()->getStudyPeriodData();
                                                    ?>
                                                </select><br>
                                                <div style="text-align:left;">&nbsp;Select 
                                  <a class="allReportLink" href="javascript:makeSelection('semesterId[]','All','allDetailsForm');">All</a> / 
                                   <a class="allReportLink" href="javascript:makeSelection('semesterId[]','None','allDetailsForm');">None</a>
                                                </div>
                                                </nobr>
                                                </td>
												<td colspan="1" valign="top" align="left" style='padding-left:10px'><nobr><b>&nbsp;&nbsp;Include</b>
                                                    <table border="0" cellpadding="2px" cellspacing="0px">
                                                        <tr>
                                                            <td><input type="checkbox" id="signature" name="signature" value="1">&nbsp;Signature</td>
                                                            <td><input type="checkbox" id="address" name="address" value="1">&nbsp;Address</td>
                                                            <td><input type="checkbox" id="branchChk" name="branchChk" checked="checked" value="1">&nbsp;Branch</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan='2'><input type="checkbox" id="sessionDateChk" name="sessionDateChk" value="1"><nobr>&nbsp;Session Start/End Date</b></nobr></td>
                                                            <td colspan='2'><input type="checkbox" id="sessionChk" name="sessionChk" value="1"><nobr>&nbsp;Session wise Semester</b></nobr></td>
                                                        </tr>
                                                        <tr>
                                                        	 <td colspan='4'><input type="checkbox" id="specializationChk" name="specializationChk" value="1"><nobr>&nbsp;<span style="font-size:18px;"></font></span></b>Specialization &nbsp;&nbsp
                                                            <input type="checkbox" id="reexamChk" name="reexamChk" value="1"><nobr>&nbsp;<span style="font-size:18px;">' * ' </font></span></b>Show Re-Exam Indication</nobr></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="contenttab_internal_rows" >&nbsp;&nbsp;<B>Sessions Per Page&nbsp;:&nbsp;</b>
                                                            <input type="radio" id="printTri1" name="printTri" value="1"><nobr>&nbsp;Single</b>
                                                            <span style="padding-left:5px">
                                                            <input type="radio" checked="checked" id="printTri2" name="printTri" value="2"><nobr>&nbsp;Double</b></nobr>
                                                            </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="6">
                                                            <table border="0" cellpadding="2px" cellspacing="0px">   
                                                                <tr>
                                                                    <td class="contenttab_internal_rows" ><B>&nbsp;Text for</b></td>
                                                                    <td class="contenttab_internal_rows" ><nobr><b><span style="font-size:18px;">' * '</span></b></nobr></td>
                                                                    <td class="contenttab_internal_rows" nowrap><b>&nbsp;:&nbsp;</b>
                                                                        <input type="text" class="inputbox" style="width:280px" id="reapparMsg" name="reapparMsg" maxlength="500">
                                                                        <!-- <input type="checkbox" id="cgpaDetails" name="cgpaDetails" value="1">&nbsp;<b>Include CGPA Details</b> -->
                                                                    </td>    
                                                                </tr>   
                                                                <tr>
                                                                    <td class="contenttab_internal_rows"><B>&nbsp;Heading</b></td>
                                                                    <td></td>
                                                                    <td class="contenttab_internal_rows" nowrap><b>&nbsp;:&nbsp;</b>
                                                                        <input type="text" class="inputbox" style="width:280px" id="headValue" name="headValue" maxlength="200">
                                                                        <!-- <input type="checkbox" id="cgpaDetails" name="cgpaDetails" value="1">&nbsp;<b>Include CGPA Details</b> -->
                                                                    </td>    
                                                                </tr>    
                                                              </table>
                                                           </td>     
                                                         </tr>  
                                                     </table>       
                                                   
                                                 </nobr>   
                                                </td>
                                                <td align="center" valign="bottom" style="padding-left:20px">
                                                    &nbsp;&nbsp;<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                </td>                                          
                                            </tr>
                                        </table>
                                       <table>
                                       <tr valign='top'>
                                       <td align='left'><div id='alertUnactive1' style="color:red;"></div>
                                      </td>
                                     </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" style="text-align:left">Student Grade Card Report :</td>
                                            <td colspan="1" class="content_title" style="text-align:right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport3()" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'>
                                    </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport3()" />
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
//$History: scStudentGradeCardContents.php $
//
//*****************  Version 14  *****************
//User: Parveen      Date: 4/07/10    Time: 2:30p
//Updated in $/Leap/Source/Templates/ScGradeCard
//new format code updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 1/12/10    Time: 3:30p
//Updated in $/Leap/Source/Templates/ScGradeCard
//degree format updated selected batch
//
//*****************  Version 12  *****************
//User: Parveen      Date: 1/12/10    Time: 2:34p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format & validation  updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 12/28/09   Time: 3:43p
//Updated in $/Leap/Source/Templates/ScGradeCard
//required paramter added
//
//*****************  Version 10  *****************
//User: Parveen      Date: 12/28/09   Time: 12:50p
//Updated in $/Leap/Source/Templates/ScGradeCard
//Session wise Trimester checkbox added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/10/09   Time: 2:29p
//Updated in $/Leap/Source/Templates/ScGradeCard
//rollno text box added
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/11/09    Time: 12:18p
//Updated in $/Leap/Source/Templates/ScGradeCard
//link remove  Include CGPA details (student Ranges are coming up which
//are indicating the no of students in a grade) 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/26/09    Time: 3:40p
//Updated in $/Leap/Source/Templates/ScGradeCard
//getBatches function added (show batches)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/29/09    Time: 2:56p
//Updated in $/Leap/Source/Templates/ScGradeCard
//student batchwise condition updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/07/09    Time: 6:54p
//Updated in $/Leap/Source/Templates/ScGradeCard
//formating, alingnment & condition updated
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/22/09    Time: 4:29p
//Updated in $/Leap/Source/Templates/ScGradeCard
//Updated formatting to display more user friendly view
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/22/09    Time: 2:04p
//Updated in $/Leap/Source/Templates/ScGradeCard
//trimester onchange remove
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/22/09    Time: 1:28p
//Updated in $/Leap/Source/Templates/ScGradeCard
//signature, address, trimester option added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/09/09    Time: 11:30a
//Created in $/Leap/Source/Templates/ScGradeCard
//file added
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/15/09    Time: 6:45p
//Updated in $/Leap/Source/Templates/ScICard
//use image for student, sms
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Templates/ScICard
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 4:29p
//Created in $/Leap/Source/Templates/ScICard
//initial checkin
//
//


?>
