<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Saurabh Thukral
// Created on : (17.08.2012 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
				<td valign="top" class="content">
					<form action="" method="POST" name="listForm" id="listForm" onsubmit="return false;">
				 		<table width="100%" border="0" cellspacing="0px" cellpadding="0" align="left">								
								<tr><td height="10px"></td></tr>								
				                <tr>
				                   <td  colspan="0" valign="top" style="padding-left:10px" >  
				                     <span class="contenttab_internal_rows"> 
				                       <nobr>
				                         <b><a href="javascript:void(0);" style="cursor:pointer;" onclick="getShowDetail();" class="link">
				                            <label id="lblMsg">Please Click to Show Advance Search</label>
				                            </a>
				                         </b>
				                          <img id="showInfo" style="cursor:pointer;" src="<?php echo IMG_HTTP_PATH; ?>/arrow-down.gif" onclick="getShowDetail(); return false;">
				                       </nobr>
				                     </span>
				                   </td>     
				                </tr>
								<tr><td height="5px"></td></tr>
								<tr id="showhideSeats" style="display:none">
									<td class="contenttab_border1" colspan="3" valign="top" style="padding-left:10px" >
										<table width="10%" border="0" cellspacing="2" cellpadding="0" height="20">
											<tr><td height="5px"></td></tr>
											<tr>
												<td class="contenttab_internal_rows" nowrap="nowrap">  
													<nobr><b>Include Classes&nbsp;:&nbsp;</b></nobr> 
												</td>      
												<td class="contenttab_internal_rows" nowrap>
													<input name="searchClassStatus" id="searchClassStatus1" value="1"  type="radio">Active&nbsp;&nbsp;&nbsp;
													<input name="searchClassStatus" id="searchClassStatus3" value="3" " type="radio">Past&nbsp;&nbsp;&nbsp;
													<input name="searchClassStatus" id="searchClassStatus4" value="4"  checked="checked" type="radio">All
												</td>
												<td class="contenttab_internal_rows" style="padding-left:5px"><nobr><strong>Roll No./Uni. RNo.&nbsp;:&nbsp;</strong></nobr></td>
												<td class="contenttab_internal_rows"><nobr>
												   <input type="text" id="rollNo" name="rollNo" class="inputbox" autocomplete='off'  maxlength="30" style="width:145px">
												   </nobr>
												</td>
												<td class="contenttab_internal_rows"><nobr><strong>Student Name&nbsp;:&nbsp;</strong></nobr></td>
												<td class="contenttab_internal_rows"><nobr><strong> 
												   <input type="text" id="studentName" name="studentName" class="inputbox" autocomplete='off'  maxlength="30" style="width:145px">
												   </nobr>
												</td>
											</tr>
											<tr><td height="5px"></td></tr>
											<tr>
                                            <td class="contenttab_internal_rows" ><nobr><strong>Approval Status&nbsp;:&nbsp;</strong></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><strong> 
                                                <select size="1" class="selectfield" name="status" id="status" style="width:135px;">
                                                    <?php 
                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                    echo HtmlFunctions::getInstance()->getStatus();
                                                    ?>
                                                </select>
                                            </td>
											<td class="contenttab_internal_rows" style="padding-left:5px"><nobr><strong>Fine Category&nbsp;:&nbsp;</strong></nobr></td>
											<td class="contenttab_internal_rows"><nobr><strong> 
												<select size="1" class="htmlElement" name="fineCategory" id="fineCategory" style="width:145px;">
											   	<option value="">All</option>
													<?php 
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getFineCategory();
													?>
												</select>
											</td>
                                            <td class="contenttab_internal_rows" style="padding-left:5px" align="left">
                                              <a href="" title="Click to Clear Date Check" alt="Click to Clear Date Check" onClick="getDateCheck(); return false;">
                                                <nobr><b>Fine Date&nbsp;:&nbsp;</b></nobr>
                                              </a>
                                            </td>
											<td class="contenttab_internal_rows" colspan="10">
                                              <table width="10%" border="0" cellspacing="0" cellpadding="0" height="20">
                                               <tr>  
                                                <td class="contenttab_internal_rows"> 
                                                    <nobr>From&nbsp;&nbsp;</nobr>
                                                </td>
											    <td class="contenttab_internal_rows"><nobr> 
												    <?php
												       require_once(BL_PATH.'/HtmlFunctions.inc.php');
												       echo HtmlFunctions::getInstance()->datePicker('startDate','');
												    ?>
                                                </td>
												<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;To&nbsp;&nbsp;</nobr></td>
                                                <td class="contenttab_internal_rows"><nobr> 
												    <?php
												       require_once(BL_PATH.'/HtmlFunctions.inc.php');
												       echo HtmlFunctions::getInstance()->datePicker('toDate','');
												    ?>
											    </td>
											    <td class="contenttab_internal_rows" style="padding-left:35px"><nobr>
												    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getFineStudent(); return false;"/>
												    </nobr> 
											     </td>
                                               </tr>	
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="14" height="5px"></td>
								</tr>	
				    </table>
                  </td>     
                    </tr>   
					<tr id="showTitle">
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Student Fine Approval Detail : </td>
								<td width="14%" class="content_title" align="right" nowrap="nowrap" style="border:0px;margin-right:0px;">
									<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: white; font-weight: bold;  "> 
										<?php echo REQUIRED_FIELD ?>&nbsp;Click to change fine amount
									</span> 
								</td>
								<td>
									<table width="60%" border="0" cellspacing="0" cellpadding="0" align="right">
										<tr id="legend12">
											<?php 
											require_once($FE . "/Library/common.inc.php");
											echo '<td style="font-size:12px;font-weight:bold;"  align= "right" width="10%" colspan="6" class="content_title">Legends : </td>
											<td style="font-size:11px" width="10%" align= "right"><img src='.IMG_HTTP_PATH.'/approved.gif border="0" alt="Approved" title="Approved"  width="15" height="15" style="cursor:default;margin-bottom: -2px;" >Approved</td>
											<td style="font-size:11px" width="10%" align= "right"><img src='.IMG_HTTP_PATH.'/rejectSmall.gif border="0" alt="Reject" title="Rejected"  width="15" height="15" style="cursor:default;margin-bottom: -2px;">Rejected</td>
											<td style="font-size:11px" width="10%" align= "right"><img src='.IMG_HTTP_PATH.'/cancelled.gif border="0" alt="Unapproved" title="Unapproved"  width="15" height="15" style="cursor:default;margin-bottom: -2px;">Unapproved</td>';
											?>
										</tr>
									</table>
								</td>								
							</tr>
							</table>
						</td>
					</tr>
					 <tr id="showData">
						<td class="contenttab_row" valign="top" colspan="2">
							<div id="results"></div>
						</td>
					 </tr>
					 <tr><td height="5px"></td></tr>
					 <tr id = 'saveDiv1'>
						<td class="contenttab_internal_rows" align="left"><b>Status:</b>
							<select size="1" class="selectfield" name="statusUpdate" id="statusUpdate" >
								<?php 
									require_once(BL_PATH.'/HtmlFunctions.inc.php');
									echo HtmlFunctions::getInstance()->getStatus('',1);
								?>
							</select>
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="validateAddForm();return false;" align="top" />
						</td>
						<td class="content_title" align="right" ><input type="image"  title="Print" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();return false;" />&nbsp;<input type="image"  name="printFineApproval" id='generateCSV' title="Export to Excel" onClick="printCSV();return false;" value="printFineApproval" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
						</td>
					</tr>
					 <tr>
						<td height="5" colspan="2"></td>
					 </tr>
					 <tr>
					 <td>
						 <table width="10%" border="0" cellspacing="0" cellpadding="0">
							 <tr id="legend" >
								<?php 
								require_once($FE . "/Library/common.inc.php");
								echo '<td nowrap style="font-size:12px;font-weight:bold" width="10%" colspan="3">Legends : </td></tr>
								<tr id="approve" ><td nowrap style="font-size:11px" width="10%"><img src='.IMG_HTTP_PATH.'/approved.gif border="0" alt="Approved" title="Approved" width="15" height="15" style="cursor:default;margin-bottom: -2px;" >Approved</td>
								<td nowrap style="font-size:11px" width="10%"><img src='.IMG_HTTP_PATH.'/rejectSmall.gif border="0" alt="Reject" title="Rejected" width="15" height="15" style="cursor:default;margin-bottom: -2px;">Rejected</td>
								<td nowrap style="font-size:11px" width="10%"><img src='.IMG_HTTP_PATH.'/cancelled.gif border="0" alt="Unapproved" title="Unapproved" width="15" height="15" style="cursor:default;margin-bottom: -2px;">Unapproved</td>';
								?>
							</tr>
						</table>
					</td>
					</tr>
				</table>
			</form>	
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

<?php floatingDiv_Start('ViewReason','Reason Description','1',''); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
       <form name="viewReason" action="" method="post"> 
            <tr><td height="5px"></td></tr>
            <tr>
	            <td width="100%"  align="left" >
    	            <div id="innerReason" style="overflow:auto; width:380px;" ></div><br>
	            </td>
            </tr>
            <tr><td height="5px"></td></tr>
        </form>
    </table>
<?php floatingDiv_End(); ?>


<?php floatingDiv_Start('ViewAmount','Modify Amount','1',''); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
       <form name="viewAmount" action="" method="post" onsubmit="return false;"> 
       	<tr>
       		<td>
       			<input type="hidden" name="studentId" id="studentId" value="" />
       		</td>
       	</tr>
	  <tr>
       	   <td width="20%" class="contenttab_internal_rows" valign="top">
    	    <nobr><b>Fine Amount(In Rs.)<?php echo REQUIRED_FIELD; ?></b></nobr>
	   </td>
          <td class="padding"><b>:</b></td>
          <td width="30%" class="padding">
          <input type="text" id="changeAmount" name="changeAmount" class="inputbox1" maxlength="10" size="35"/>
          </td>
          </tr> 
	  <tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="editFineAmount();" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ViewAmount');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>           
        </form>
    </table>
<?php floatingDiv_End(); ?>


<?php floatingDiv_Start('ViewStatusReason','Status Reason Description','3',''); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	    <form name="viewStatusReason" action="" method="post"> 
            <tr><td height="5px"></td></tr>
            <tr>
	            <td width="100%"  align="left">
	              <div id="innerStatusReason" style="overflow:auto; width:380px;" ></div><br>
	            </td>
            </tr>
            <tr><td height="5px"></td></tr>
       </form>
    </table>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('AddStatusReason','','2'); ?>
    <form name="addStatusReason" action="" method="post">
	    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		    <tr>
			    <td width="15%" class="contenttab_internal_rows"><nobr><b>&nbsp;Enter Reason<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
			    <td width="45%" class="padding">
                    <textarea name="appproveReason" id="appproveReason" cols="30" rows="5" onkeyup="return ismaxlength(this)" maxlength="100"  style="vertical-align:middle;"></textarea>
                </td>
		    </tr>
		    <tr>
			    <td colspan="2" height="5px"></td></tr>
		    <tr>
			    <td align="center" style="padding-right:10px" colspan="2">
			    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddFineForm(this.form,'Add');return false;" />
			    </td>
		    </tr>
		    <tr>
			    <td colspan="2" height="5px"></td></tr>
		    <tr>
	    </table>
    </form>
<?php floatingDiv_End(); ?>
