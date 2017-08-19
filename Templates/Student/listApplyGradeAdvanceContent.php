<?php 
//--------------------------------------------------------
//This file creates Html Form output for marks not entered report
//
// Author :Ajinder Singh
// Created on : 23-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php require_once(BL_PATH . "/messages.inc.php");?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
                       <?php require_once(TEMPLATES_PATH."/breadCrumb.php");?>
                    </td>
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
						<form name="marksNotEnteredForm" action="" method="post" onSubmit="return false;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
								<tr>
									<td valign="top" class="contenttab_row1">
											<table align="center" border="0" cellpadding="2px" cellspacing="2px">
												<tr>
													<td valign="top" class="contenttab_internal_rows"><nobr><b>Time Table</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows">
                                                    <select size="1" style="width:220px" class="inputbox1" name="labelId" id="labelId" onBlur="getLabelClass()">
                                                    <option value="">Select</option>
                                                    <?php
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                      echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                                    ?>
                                                    </select></td>
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Class</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows">
                                                        <select class="htmlElement2" name="degreeId" id="degreeId" style="width:340px;" onBlur="getClassSubjects();">
                                                        <option value="">Select</option>
                                                        </select>
                                                    </td>
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows">
                                                        <select size="1" class="htmlElement" style="width:190px;" name="subjectId" id="subjectId">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </td>
                                                  </tr>
                                                  <tr>  
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>Rounding</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                    <td valign="top" class="contenttab_internal_rows">
														<select  style="width:220px" name="gradingFormula"  class="htmlElement" id="gradingFormula"  onChange="hideResults();"  style="width:80px;">
															<option value="">Select</option>
															<option value="ceil">Round Up</option>
															<option value="floor">Round Down</option>
															<option value="round">Round Off</option>
															<option value="round2">Round 2 Decimals</option>
														</select>
													</td>
													<td class="contenttab_internal_rows" colspan="4" class="" valign="top">
														<span style="padding-left:25px" >
														<input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
													</td>
												</tr>
											</table>   
                                            <table align="left" border="0" cellpadding="0">                                                                                                    
                                              <tr>
                                                <td valign="top"  class="contenttab_internal_rows" colspan="2">  
                                                    <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10px; color: red;"> 
                                                       <b>Notification:</b>
                                                    </span>   
                                                </td>       
                                              </tr>
                                              <tr>
                                                 <td valign="top"  class="contenttab_internal_rows"><nobr><b>*</b><nobr></td>
                                                 <td valign="top"  class="contenttab_internal_rows" nowrap>
                                                  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10px; color: red;"> 
                                                    View only those Classes Which have already been transfered marks
                                                  </span>  
                                                 </td>   
                                              </tr>   
                                              <tr>
                                                 <td valign="top"  class="contenttab_internal_rows"><nobr><b>*</b><nobr></td>
                                                 <td valign="top"  class="contenttab_internal_rows" nowrap>
                                                  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10px; color: red;"> 
                                                    Define Grade Set and Grading 
                                                  </span>  
                                                 </td>   
                                              </tr>   
                                              <tr>
                                                 <td valign="top"  class="contenttab_internal_rows"><nobr><b>*</b><nobr></td>
                                                 <td valign="top"  class="contenttab_internal_rows" nowrap>
                                                  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10px; color: red;"> 
                                                    Apply Grading Scale for Internal And External Marks 
                                                  </span>
                                                 </td>   
                                              </tr>   
                                           </table>  
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr id='graphRow' style='display:none;'>
									<td colspan='2' class='contenttab_row'>
										<div id = 'graphDiv' style='width:980px;overflow:auto;'></div>
										<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save_graph.gif" onClick="exportImage()" />
									</td>
								</tr>
								<tr id='nameRow' style='display:none;'>
									<td colspan="2" class="" height="20" >
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
											<tr>
												<td colspan="1" class="content_title">Apply Grade : </td> 
												<td colspan="1" class="content_title" align="right"></td>
											</tr>
										</table>
									</td>
								</tr>
                                <tr id='sliderRow1' style='display:none;'>
			                      <td colspan='1' class='contenttab_row'>
                                    <div style="margin-top: 5px;" class="redLink">
                                       <strong>&nbsp;Note:&nbsp;</strong><?php echo NO_INTERNAL_TOTAL_MARKS ;?>
                                    </div>
                                  </td>			
		                        </tr>
								<tr id='sliderRow' style='display:none;'>
                                  <td colspan='1' class='contenttab_row'>
                                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
									    <td colspan='1' class='contenttab_row' width="50%">
                                           <div id='manualDiv' style='height:250px;display:none;overflow:auto;'></div>
									    </td> 
								        <td colspan='1' class='' id='resultRow' style="display:none"  width="50%">
                                           <div id='resultsDiv1' style='height:250px;display:none;overflow:auto;'></div>
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
<!-- form table ends -->

<?php floatingDiv_Start('PendingStudentListDiv','Marks have not been transferred for following students'); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
      <tr>
        <td class="contenttab_internal_rows">
           <div style="overflow:auto; width:500px; height:350px; vertical-align:top;">
              <div id="marksNotTransferStudentList" style="width:98%; vertical-align:top;"></div>
           </div>
        </td>
      </tr>    
    </table>
</form>
<?php floatingDiv_End(); ?>

           