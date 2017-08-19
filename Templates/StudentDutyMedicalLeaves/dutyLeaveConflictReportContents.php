<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
       <td valign="top" class="title">
		<?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
	</td>	
   </tr>
   <tr>
	<td valign="top">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
	      <tr>
		 <td valign="top" class="content">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			   <td class="contenttab_border" height="20">
			     <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
				<tr>
				   <td class="content_title">Duty Leave Conflict Report : </td>
				   <td class="content_title"></td>
				</tr>
			      </table>
			    </td>
			  </tr>
			  <tr>
			    <td class="contenttab_row" valign="top" >
			    <form name="conflictReportForm" id="conflictReportForm" method="post" action="" onsubmit="return false;" >
			       <table border="0" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
				     <td>
					<table align="left" border="0" cellpadding="5px" cellspacing="0"  >
					   <tr>
					     <td class="contenttab_internal_rows"><nobr><b>Time Table</b></nobr></td>
					     <td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
					       <td class="padding"><nobr>
					       <select size="1" class="selectField" name="labelId" id="labelId" style="width:250px;" onchange="getTimeTableClasses(this.value);getDutyEvent(this.value)">
					       <option value="" >Select</option>
					       <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
					       echo HtmlFunctions::getInstance()->getTimeTableLabelData();
					       ?>
					       </select></nobr>
					       </td>
					      <td class="contenttab_internal_rows">
					      <nobr><b>Class</b></nobr>
					      </td>
					      <td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td> <td class="padding"><nobr>
					      <select size="1" class="selectField" name="classId" id="classId" style="width:250px;" onChange="hideResults();getClassSubjects();">
						<option value="">Select</option>
						<?php
						//require_once(BL_PATH.'/HtmlFunctions.inc.php');
						//echo HtmlFunctions::getInstance()->getSelectedTimeTableClasses();
						?>
						</select></nobr>
						</td>
						<td class="contenttab_internal_rows">
						<nobr><strong>Event</strong></nobr>
						</td>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
						<td class="padding"><nobr>
						<select name="eventId" id="eventId"  class="selectField"  onChange="hideResults();"  style="width:250px;" >
						<option value="-1">Select</option>
						</select></nobr>
						</td>
					     </tr>
					     <tr>
					        <td class="contenttab_internal_rows">
						<nobr><strong>Subjects</strong></nobr>
						</td>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
						<td class="padding"><nobr>
						<select name="subjectId" id="subjectId"  class="selectField"  onChange="hideResults();"  style="width:250px;" >
						<option value="-1">Select</option>
						</select></nobr>
						</td>
                                                <td class="contenttab_internal_rows">
						<nobr><strong>Roll No.</strong></nobr>
						</td>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
						<td class="padding">
						<nobr><input name="rollNo" id="rollNo" type="text" class="inputBox" style="width:245px;" ></nobr>
                        </td>
                        <td style:width="100px">
                        <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateData(this.form);return false;" />
                        </td>
						   <table>
                                                </tr>
                             			<tr>
						  <td class="contenttab_internal_rows"><nobr><strong>Show&nbsp;:&nbsp;</strong></nobr>
						  
						 <input type="radio" name="showConflict" value="1" checked="checked" onclick="hideResults();" />Conflicted Data&nbsp;
						 <input type="radio" name="showConflict" value="0" onclick="hideResults();" />Non Conflicted Data&nbsp;
						  <input type="radio" name="showConflict" value="-1" onclick="hideResults();" />Both  
                                    
						</td>
						<td colspan=3 class="contenttab_internal_rows" nowrap>
                           			    <table align="left" border="0" cellpadding="0px" cellspacing="0" >
                             			       <tr>
                                                          <td class="contenttab_internal_rows" style="display:none">
                               				  <nobr><strong>&nbsp;Display Records&nbsp;:&nbsp;</strong></nobr>
                                  		       	  <td class="padding"  style="display:none"><nobr>
                                     			  <select size="1" class="selectField" name="displayRecord" id="displayRecord" style="width:110px;">
                                			  <option value="1">Above Limit</option>
                               				  <option value="2">Below Limit</option>
                                			  <option selected="selected" value="3">All Records</option>
                           				  </select></nobr>
                        				  </td>
							  <td colspan=3 class="contenttab_internal_rows" nowrap>
							    <table align="left" border="0" cellpadding="0px" cellspacing="0" >
								<tr>
								   <td class="padding" style="padding-left:15px;">
								   
								    </td>
							          </tr>
							   </table>       
							 </td>
						      </tr> 
						   </table>
					        </td>
					      </tr>
					      <tr>
						 <td class="contenttab_internal_rows" align="left" valign="top">
						 <fieldset>
						 <b><u>Please Note:</u><br></b>
						  <font color="red">1. This Conflict and Non Conflict Report is ONLY applicable for <u>Daily Attendance</u> and NOT for Bulk Attendance.</font><br/>
						  <font color="red">2. This report displays ALL the duty leaves uploaded and ANY number of leaves can be approved. </font><br>
						  <font color="red">3. If a CONFLICTED Duty Leave is APPROVED then benefit will <b><u>NOT</u></b> be given in the Attendance. </font><br>
 <font color="red">4. This Conflict and Non Conflict Report is ONLY applicable for those days for which attendance has been taken. </font>
						  </fieldset>
						  </td>
						</tr>
						<tr> 
			       			  <td class="contenttab_internal_rows" align="left" valign="top">
						     <table width="100%" border="0px" cellpadding="0" cellspacing="0">
							<tr>
							      <td class="contenttab_internal_rows" colspan="20" >
							      <b><a href="" class="link" onClick="getShowDetail(); return false;" >
				      <Label id='sampleFormat'>Click here for Details on Conflict and Non-Conflict Report</label></b></a>
							      </td>
							  </tr> 
						      </table>
						   </td>
						</tr>
					      </table>                      
					    <div id="scrollDiv" style="overflow:auto; HEIGHT:500px; vertical-align:top;">  
					       <div id='results'></div>
					    </div>  
					 </td>
				      </tr>
				   </table>  
			        </form> 
			     </td>
			  </tr>
			  <tr id="savePrintRowId" style="display:none;">
			      <td>
				 <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
				     <tr>
					 <td class="content_title" valign="middle" align="center" width="20%">
					 <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/save.gif" onClick="doDutyLeave();" >&nbsp;
					 <span style="display:none" id="printSpanId">
					 <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
					 <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
					 </span>  
					 </td>  
				      </tr>
				  </table>
			       </td>
			   </tr>
		</table>
	</td>
</tr>
</table>
</td>
</tr>
</table>


<?php floatingDiv_Start('showHelpMessage','Conflict Details','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td>
    </tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll" style="overflow:auto; width:500px; height:225px; vertical-align:top;">
                <div id="divshowHelp" style="width:98%; vertical-align:top;">
		    <table width="100%" border="0px" cellpadding="0" cellspacing="0">
			<tr>
			   <td class="contenttab_internal_rows">
			  	<?php global $FE; $conflictUrlInstruction = $FE . "/Templates/Conflict_NonConflictInstructions.php";
				include "$conflictUrlInstruction";
				?>
			   </td>
			</tr> 
		     </table>	
                   </div>
               </div>
           </td>
        </tr>
</table>
<?php floatingDiv_End(); ?>    
    
