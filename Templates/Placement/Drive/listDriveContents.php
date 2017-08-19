<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR UNIVERSITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (14.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddDriveDiv','',650,250,200,100);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
    <!--Start Add Div-->

<?php floatingDiv_Start('AddDriveDiv','Add Placement Drive Details'); ?>
<form name="AddDrive" id="AddDrive" action="" method="post" style="display:outline" onsubmit="return false;">
<input type="hidden" name="placementDriveId" id="placementDriveId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Placement Drive Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding" colspan="4">
        <input type="text" name="driveCode" id="driveCode" class="inputbox" style="width:99%" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Company<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding" colspan="4">
        <select name="companyId" id="companyId" class="inputbox" style="width:100%">
         <option value="">Select</option>
         <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getPlacementCompanies(); 
         ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>From Date</b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
        ?>
        &nbsp;Time<?php echo REQUIRED_FIELD; ?> : &nbsp;
        <input type="text" id="startTime" name="startTime" class="inputbox" style="width:50px" maxlength="5"/>
        <select size="1" name="startAmPm" id="startAmPm"  class="selectfield" style="width:45px">
            <option value="AM" width="10%">AM</option>
            <option value="PM">PM</option>
            </select>
        </td>
        <td width="1%" class="contenttab_internal_rows"><nobr><b>To Date :&nbsp;&nbsp;</b></nobr><?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
        ?>
		</td>
               <td width="78%" class="padding">
        
        &nbsp;Time<?php echo REQUIRED_FIELD; ?> : &nbsp;
        <input type="text" id="endTime" name="endTime" class="inputbox" style="width:50px" maxlength="5"/>
        <select size="1" name="endAmPm" id="endAmPm" class="selectfield" style="width:45px">
            <option value="AM" width="10%">AM</option>
            <option value="PM">PM</option>
            </select>
        </td>
        </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows"><nobr><b>Visiting Persons<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" >:</td>
       <td class="padding" colspan="4">
        <input type="text" name="visitingPerson" id="visitingPerson" class="inputbox" style="width:99%" maxlength="250" />
      </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows"><nobr><b>Venue<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" >:</td>
       <td class="padding" colspan="4">
        <input type="text" name="venue" id="venue" class="inputbox" style="width:99%" maxlength="250" />
      </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Eligibility Criteria</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="eligibilityCriteria" id="eligibilityCriteria1" value="1" onclick="toggleEligibilityCriteria(true);" />Yes &nbsp;
         <input type="radio" name="eligibilityCriteria" id="eligibilityCriteria2" value="0" onclick="toggleEligibilityCriteria(false);" checked="checked" />No
        </td>

      <td class="contenttab_internal_rows" colspan="3"><nobr>
			<table width="10%" border="0" cellspacing="0" cellpadding="0" class="border">
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>Cutoff marks in(%)</b></nobr></td>
					<td class="padding">:</td>
					<td class="padding">	
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;10th</b></nobr></td>
					<td class="contenttab_internal_rows"><nobr>
					<input type="text" name="cutOff1" id="cutOff1" class="inputbox" style="width:35px" maxlength="5" disabled="disabled" />&nbsp;&nbsp;&nbsp;
					</nobr></td>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;12th</b></nobr></td>
					<td class="contenttab_internal_rows"><nobr>
					<input type="text" name="cutOff2" id="cutOff2" class="inputbox" style="width:35px" maxlength="5" disabled="disabled" />&nbsp;&nbsp;&nbsp;
					</nobr></td>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;Last Sem.</b></nobr></td>
					<td class="contenttab_internal_rows"><nobr>
					<input type="text" name="cutOff3" id="cutOff3" class="inputbox" style="width:35px" maxlength="5" disabled="disabled" />&nbsp;&nbsp;&nbsp;
					</nobr></td>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;Graduation</b></nobr></td>
					<td class="contenttab_internal_rows"><nobr>
					<input type="text" name="cutOff4" id="cutOff4" class="inputbox" style="width:35px" maxlength="5" disabled="disabled" />
					</nobr></td>
				</tr>
			</table> 
      <!--  <td class="contenttab_internal_rows"><nobr><b>Cutoff marks in </b></nobr></td>
        <td class="padding">:</td> 
        <td class="padding">
         <input type="text" name="cutOff1" id="cutOff1" class="inputbox" style="width:25px" maxlength="5" disabled="disabled" />&nbsp; 10th
         <input type="text" name="cutOff2" id="cutOff2" class="inputbox" style="width:25px" maxlength="5" disabled="disabled" />&nbsp; 12th 
         <input type="text" name="cutOff3" id="cutOff3" class="inputbox" style="width:25px" maxlength="5" disabled="disabled" />&nbsp; last sem.
         <input type="text" name="cutOff4" id="cutOff4" class="inputbox" style="width:25px" maxlength="5" disabled="disabled" /> graduation &nbsp;

        </td> -->
   
    <tr>
     <td class="contenttab_internal_rows"><nobr><b>Test</b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
         <input type="radio" name="isTest" id="isTest1" value="1" onclick="toggleTest(true);" />Yes &nbsp;
         <input type="radio" name="isTest" id="isTest2" value="0" onclick="toggleTest(false);" checked="checked" />No
      </td>
    </tr>
    <tr>
     <td colspan="6">
     <fieldset>
      <legend class="contenttab_internal_rows"><b>Enter test subjects and duration</b>&nbsp;(if applicable)</legend>
      <div style="height:150px;overflow:auto">
       <table border="0" cellpadding="0" cellspacing="0">
       <?php
        for($i=0;$i<10;$i++){
       ?>
        <tr>
         <td class="contenttab_internal_rows" style="padding-left:10px;"><b><?php echo ($i+1);?>.</b></td>
         <td class="contenttab_internal_rows" ><b>Test Subject</b></td>
         <td class="padding">:</td>
         <td class="padding">
          <input type="text" name="testSubject<?php echo $i;?>" id="testSubject<?php echo $i;?>" class="inputbox" maxlength="100" style="width:450px;" disabled="disabled" />
         </td>
         <td class="contenttab_internal_rows"><b>Duration </b>(in hours)</td>
         <td class="padding">:</td>
         <td class="padding">
          <input type="text" name="testDuration<?php echo $i;?>" id="testDuration<?php echo $i;?>" class="inputbox" maxlength="20" disabled="disabled" />
         </td>
        </tr>
        <?php
        }
        ?> 
       </table>
      </div>
     </fieldset> 
     </td>
    </tr>
    
    <tr>
     <td class="contenttab_internal_rows" ><nobr><b>Group Discussion</b></nobr></td>
       <td class="padding">:</td>
       <td class="padding">
        <input type="radio" name="groupDiscussion" id="groupDiscussion1" value="1" onclick="toggleGD(true);" />Yes &nbsp;
        <input type="radio" name="groupDiscussion" id="groupDiscussion2" value="0" onclick="toggleGD(false);" checked="checked" />No
      </td>
      <td class="contenttab_internal_rows" ><nobr><b>Discussion Duration</b></nobr>
       <td class="padding" width="200px" >:&nbsp;&nbsp;&nbsp;
         <input type="text" name="discussionDuration" id="discussionDuration" class="inputbox" maxlength="15" disabled="disabled" />
      </td>
    </tr>
    
    <tr>
     <td class="contenttab_internal_rows" ><nobr><b>Technical Interview</b></nobr></td>
       <td class="padding">:</td>
       <td class="padding">
        <input type="radio" name="individualInterview" id="individualInterview1" value="1" onclick="toggleInterview(true);" />Yes &nbsp;
        <input type="radio" name="individualInterview" id="individualInterview2" value="0" onclick="toggleInterview(false);" checked="checked" />No
      </td>
      <td class="contenttab_internal_rows" ><nobr><b>Interview Duration</b></nobr>
      <td class="padding" width="200px" >:&nbsp;&nbsp;&nbsp;
        <input type="text" name="interviewDuration" id="interviewDuration" class="inputbox" maxlength="15" disabled="disabled" />
      </td>
    </tr>
    
    <tr>
     <td class="contenttab_internal_rows" ><nobr><b>HR Interview</b></nobr></td>
       <td class="padding">:</td>
       <td class="padding">
        <input type="radio" name="hrInterview" id="hrInterview1" value="1" onclick="toggleHRInterview(true);" />Yes &nbsp;
        <input type="radio" name="hrInterview" id="hrInterview2" value="0" onclick="toggleHRInterview(false);" checked="checked" />No
      </td>
      <td class="contenttab_internal_rows" ><nobr><b>Interview Duration</b></nobr>
      <td class="padding" width="200px" >:&nbsp;&nbsp;&nbsp;     
      <input type="text" name="hrInterviewDuration" id="hrInterviewDuration" class="inputbox" maxlength="15" disabled="disabled" />
      </td>
    </tr>
    
   
    
    <tr>
     <td align="center" style="padding-right:10px" colspan="6">
      <input type="image" name="imageAdd" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" tabindex="16"/>
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDriveDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="17" />
    </td>
   </tr>
   <tr><td colspan="6" height="5px"></td></tr>
</table>
</form>
 <?php floatingDiv_End(); ?>
<!--End Add Div-->
                                                           
<?php
// $History: listDriveContents.php $
?>