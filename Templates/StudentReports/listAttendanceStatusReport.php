<?php 
//--------------------------------------------------------
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
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
							<tr >
								<td valign="top" class="contenttab_row1">
									<form name="attendanceMissedForm" id="attendanceMissedForm" action="" method="post" onSubmit="return false;">
										<table align="left" width="100%" border="0" cellpadding="0" cellpadding="0" >
                                        <tr style="display:none">
                                           <td class="contenttab_internal_rows" align="left" colspan="10"  width="2%">
                                             <table align="left" width="10%" border="0" cellpadding="0" cellpadding="0" >
                                                <tr>
                                                   <td class="contenttab_internal_rows" align="left">
                                                       <nobr><b>Strictly Time Table Wise</b></nobr>
                                                    </td>
                                                    <td class="contenttab_internal_rows1" align="left" ><nobr><b>:&nbsp;</b></nobr></td> 
                                                    <td class="contenttab_internal_rows1" width="2%"  >
                                                      <nobr>&nbsp;<input checked="checked" id="timeTableCheck" name="timeTableCheck" type="checkbox" ></nobr>
                                                    </td> 
                                                 </tr>
                                               </table>
                                            </td>         
                                        </tr>
						<tr>
			  <td class="contenttab_internal_rows1"  align="left"><nobr><b>Time Table</b></nobr></td>
                            <td class="contenttab_internal_rows1" align="left" ><nobr><b>:&nbsp;</b></nobr></td>
				<td class="contenttab_internal_rows1" >
				<select size="1" class="inputbox1" style="width:220px" name="labelId" id="labelId" onchange="getPopulate('T');return false;">
				<option value="" >Select</option>
				<?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
				?>
				</select>
				</td>
				<td class="contenttab_internal_rows1" align="left" >
					<nobr><b>Class</b></nobr>
				</td>
				<td class="contenttab_internal_rows1"  align="left"><nobr><b>:&nbsp;</b></nobr></td>
				<td class="contenttab_internal_rows1" >
				<select size="1" name="degree" id="degree" class="inputbox1" onchange="getPopulate('C');return false;" style="width:220px">
					<option value="">Select</option>
				</select>
				</td>
							
			</tr>
                        <tr>
			<td class="contenttab_internal_rows1" align="left">
				<strong>Subject</strong>
			</td>
			<td class="contenttab_internal_rows1" align="left"><nobr><b>:&nbsp;</b></nobr></td> 
			<td class="contenttab_internal_rows1">
			<select name="subjectId" class="selectfield" id="subjectId" style="width:220px" onchange="getPopulate('S'); return false;">														<option value="">Select</option>
				</select>
			</td>
                        <td class="contenttab_internal_rows1" align="left"><nobr><b>Teacher</b></nobr></td>
                        <td class="contenttab_internal_rows1" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                        <td class="contenttab_internal_rows" colspan="12"><nobr>
                            <table width="10%" border="0" cellspacing="0" cellpadding="0" >   
                              <tr> 
                                 <td class="contenttab_internal_rows1" width="2%">
                                    <nobr>
                                    <select size="1" class="inputbox1" style="width:220px" name="employeeId" id="employeeId">
                                        <option value="" >Select</option>
                                        <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                        ?>
                                     </select>
                                     </nobr>
                                  </td>
				
                                  <td class="contenttab_internal_rows" align="left" style="padding-left:10px"  width="2%">
                                    <nobr>Attendance Not Marked Today</nobr>
                                  </td>
                                  <td class="contenttab_internal_rows1" width="2%">
                 <nobr>&nbsp;<input id="showTodayAttendance" name="showTodayAttendance" type="checkbox" onclick="getShowDate();"></nobr>
                                  </td>  
				    
                                  <td class="contenttab_internal_rows" align="left" style="padding-left:20px;" width="64%">
                                     <nobr>
                                     <span id="showDate" style="display:none">
                                        <b>Date:&nbsp;</b>           
                                        <label id='lblDate'><?php echo UtilityManager::formatDate(date('Y-m-d')); ?></label>
                                        <input type="hidden" name="txtDate" id="txtDate" value="<?php echo date('Y-m-d'); ?>">
                                     </span>&nbsp;   
                                     </nobr>
                                  </td>
                                  <td class="contenttab_internal_rows1" style="padding-left:20px" width="30%">
                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                  </td>
                                 </tr>
                               </table>
                              </nobr>
                        </td>    
					    </tr>
                        </table>
						</form>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Last Attendance Taken Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
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
<?php 
//$History:
?>
