<?php 
//-------------------------------------------------------
//  This File outputs Audit Trail Report Form
//
// Author :Kavish Manjkhola
// Created on : 24-Mar-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
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
									<form name="auditTrailReport" action="" method="post" onSubmit="return false;">
                                        <table width="70%" align="center" border="0" cellpadding="0px" cellspacing="0" >
                                            <!-- <tr> -->
                                               <td class="padding" align="left" nowrap>
                                                    <strong>From Date</strong>  
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td class="padding" align="left" nowrap>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                        echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                    ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>    
                                                <td class="padding" align="left" nowrap>         
                                                    <strong>To Date</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td class="padding" align="left" nowrap>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                        echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                                    ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Audit Type</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td align="left" class="padding" nowrap colspan="2">
												<select name="auditType">
                                                   <option value="">All</option>
												   <?php
												   require_once($FE . "/Library/common.inc.php");
												   $ctr = count($auditTrailArray);
												   if($ctr) {
														foreach($auditTrailArray as $key => $values) {
															echo "<option value='$key'>".$values."</option>";
														}
												   }
												   ?>
                                                </select>
                                               </td>
                                               <!-- </tr> -->
                                                 <td align="left" class="padding" nowrap>
                                                    &nbsp;&nbsp;
                                                     <input type="image" name="auditTrailSubmit" value="auditTrailSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                </td>
                                              </tr>
                                            <tr>
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
											<td colspan="1" class="content_title">Audit Trail Report :</td>
											<td class="content_title" align="right">
                  <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                  <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                                </td>
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
											<td colspan="2" class="content_title" align="right">
              <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
              <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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