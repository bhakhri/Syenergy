<?php 
//This file creates Html Form output for Class Wise Evaluation Report
//
// Author :Aditi Miglani
// Created on : 09 Aug 2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
							<tr>
								<td valign="top" class="contenttab_row1">
									<form name="studentExternalMarksFrm" action="" method="post" onSubmit="return false;">
									    <table width="60%" align="left" border="0">
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
                                                    <select size="1" class="selectfield" name="classId" id="classId" style="width:250px" onchange="getShowSubject(); return false;">
                                                        <option value="">Select</option>
                                                     </select>
                                                    </nobr>
                                                </td>
                                                <td class="contenttab_internal_rows" align="left" style="padding-left:20px" nowrap>  
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                </td>
                                            </tr>
                                            <tr id='showSubjectEmployeeList' style='display:none;'>  
                                              <td class="contenttab_internal_rows" align="left" colspan="20">
                                                  <table style='display:none;' width="100%" >
                                                     <tr>
                                                          <td class="contenttab_internal_rows">
                                                              <b><a href="" class="link" onClick="getShowDetail(); return false;" >
															   <Label id='idSubjects'>Show Subject & Teacher Details</label></b></a>
															   <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                          </td>
                                                     </tr> 
                                                     <tr>
                                                          <td class="contenttab_internal_rows" id='showSubjectEmployeeList11'>
                                                            <span id='subjectTeacherInfo'></span>
                                                          </td>
                                                     </tr> 
                                                  </table>
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
											<td colspan="1" class="content_title">Evaluation Details :</td>
											<td colspan="1" class="content_title" align="right">
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" name="studentCSV" value="studentCSV"     src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
										</tr>
									</table>
								</td>
							</tr>
                            <tr id='resultRow' style='display:none;'>
                                <td valign='top'  colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1000px; height:520px; vertical-align:top;">
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
                                                <input type="image" name="studentPrint" value="studentPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" name="studentCSV" value="studentCSV"     src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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

