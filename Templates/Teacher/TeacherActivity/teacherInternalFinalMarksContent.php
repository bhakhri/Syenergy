<?php 
//This file creates Html Form output for student Final marks Foxpro Report
//
// Author :Parveen Sharma
// Created on : 28-04-09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="internalMarksFoxproFrm" action="" method="post" onSubmit="return false;">
<select size="1" class="selectfield" name="subjectId" id="subjectId" style="display:none">
</select>                                                          
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Marks & Attendance&nbsp;&raquo;&nbsp; Display Consolidated Internal Marks Report </td>
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
										<table width="40%" align="center" border="0" >
											<tr>
											    <td class="contenttab_internal_rows" align='left' nowrap><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                                <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>  
                                                <td class="contenttab_internal_rows" align="left" nowrap>
                                                <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:160px" onChange="getLabelClass()">
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
                                                    </select></nobr>
                                                </td>
                                                <td class="contenttab_internal_rows" align="right"><strong>&nbsp;&nbsp;Degree&nbsp;<?php echo REQUIRED_FIELD ?></strong></td>
                                                <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>  
                                                <td class="contenttab_internal_rows" align="left" colspan="4" nowrap>
                                                   <table align="left" border="0" cellpadding="0px" cellspacing="0px">
                                                     <tr>
                                                        <td class="contenttab_internal_rows" align="left">
                                                            <select size="1" class="selectfield" name="degree" id="degree" style="width:280px" onChange="getClassSubjects();">
                                                                <option value="">Select</option>
                                                             </select>
                                                            </nobr>
                                                        </td>
                                                        <td class="contenttab_internal_rows" align="left" style="padding-left:20px" nowrap>  
                                                            <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
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
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Display Consolidated Internal Marks Report Details :</td>
											<td colspan="1" class="content_title" align="right">
                                              <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                              <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;&nbsp;
                                            </td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv' style="overflow:auto; height:370px;" >
                                    </div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right">
                                              <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                              <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;&nbsp;
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
//$History: teacherInternalFinalMarksContent.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/24/09   Time: 12:44p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//link tag format updated
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/16/09    Time: 3:22p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//list view div formatting add
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/16/09    Time: 3:06p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//message change
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/16/09    Time: 2:54p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//inital checkin
//


?>
