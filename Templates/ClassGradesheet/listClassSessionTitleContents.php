 <?php 
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/Re-exam Label base Class Mapping
// Author : Parveen Sharma
// Created on : (12.06.2011 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form name="frmReappearMapping" id="frmReappearMapping" action="" method="post" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2"><?php require_once(TEMPLATES_PATH."/breadCrumb.php");?> </td>
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
                                <table align="center" border="0" cellspacing="0" cellpadding="0">
                                        <td style="padding-left:20px" class="contenttab_internal_rows" valign="top"><strong>Batches</strong></td>
                                        <td class="padding"  valign="top"><b>:</b></td>
                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                            <select name='batchId' id='batchId' class='inputbox1' onchange="getDegree();" style='width:150px'><option value=''>Select</option>
                                            </select><br>
                                            </nobr>
                                        </td>

 					<td style="padding-left:20px" class="contenttab_internal_rows"  valign="top">
						<strong>Degree</strong>
                    </td>
                    <td class="padding"  valign="top"><b>:</b></td>
                    <td class="contenttab_internal_rows" valign="top"><nobr>
                        <select name="degreeId" id="labelId" class="inputbox1"  onChange="getBranch();" style="width:210px">
                        <option value=''>Select</option>
                        </select></nobr>
                    </td>
  					<td style="padding-left:20px" class="contenttab_internal_rows"  valign="top">
						<strong>Branch</strong></td>
                    <td class="padding"  valign="top"><b>:</b></td>
                    <td class="contenttab_internal_rows" valign="top"><nobr>
                        <select name='branchId' id='branchId' class='inputbox1' style='width:320px'>
                        <option value=''>Select</option>
                        </select><br>
                        </nobr>
                    </td>
					<td align="center">
					<span style="padding-left:20px" >
					<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
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
											<td colspan="1" class="content_title">Class Session Details :</td>
											<td class="content_title" align="right">
				    <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="insertValue()" />&nbsp;
                  <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                  <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printCSV()" />&nbsp;
                                                </td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                                      <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right">
			  <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="insertValue()" />&nbsp;
              <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
              <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printCSV()" />&nbsp;
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
