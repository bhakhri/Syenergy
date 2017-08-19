<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
// Author : Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php

    require_once(BL_PATH.'/helpMessage.inc.php');
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        </td>
    </tr>
    <tr>
        <td valign="top">
<form name="allDetailsForm" action="" method="post" onSubmit="return false;">  
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		<tr>
		 <td valign="top" class="content">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
		 <tr>
			<td valign="top" class="contenttab_row1" align="center">
				<table border='0' width='100%' cellspacing='0'>
                    <tr>
                         <td class="contenttab_internal_rows" colspan="15">
                              <table width="50%" border="0" cellspacing="2px" cellpadding="2px" >
                             <tr>
                                    <td class="contenttab_internal_rows" style="padding-left:10px;"><nobr><b>Reg. / Univ / Roll No.</b><?php echo REQUIRED_FIELD; ?></nobr></td>
                                    <td class="contenttab_internal_rows" width="1%" align='center'><nobr><b>:</b></nobr></td>
                                    <td class="contenttab_internal_rows">
                                        <nobr><input  onchange="getClass();resetResult();" type="text" id="rollNo"  name="rollNo" class="inputbox" style="width:120px" maxlength="50"></nobr>
                                    </td>
                                    <td  class="contenttab_internal_rows" style="padding-left:10px;"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		                    <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
		                    <td  class="contenttab_internal_rows" width="2%">  
		                        <select name="classId" id="classId" style="width:250px" class="selectfield" onchange="resetResult();">
		                            <option value="">Select</option>
		                        </select>
		                    </td>
                                    <td class="contenttab_internal_rows" style="padding-left:10px">
                                       <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                    </td> 
                                 </tr>
                                 <tr><td height="5px"></td></tr>
                                 <tr id='adhocChargesHidden' style="display:none" >
                                  	
                                       
                                     <td class="contenttab_internal_rows" style="padding-left:10px;"><nobr><b>Format<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		                    <td  class="contenttab_internal_rows" width="1%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
		                     <td class="contenttab_internal_rows" valign='top'><input onclick="getAdhocCharges();" type='radio' name='formatRadio' id='percentRadio'><b>%</b>
                                       &nbsp;&nbsp;<input type='radio' onclick="getAdhocCharges();" checked='true' name='formatRadio' id='fixedRadio'><b>Fixed</b></td>
                                       
                                       <td class="contenttab_internal_rows" style="padding-left:10px"><nobr><b>Concession Amount</b></nobr></td>
                                       <td width="1%" align='center'><nobr><b>:</b></nobr></td>
                                       <td class="contenttab_internal_rows"><nobr>
                                           <input onkeyup="getAdhocCharges();"  type="text" style="width:250px" id="adhocCharges" name="adhocCharges" class="inputbox" maxlength="10" />
                                         </nobr>
                                       </td>
                                     <td class="contenttab_internal_rows" style="padding-left:12px;display:none;" id="deleteConcession"> 
                                     <a href="#" onClick="return deleteConcession();return false;"><nobr>
                                     	<img name="deleteStudentConcession" value="deleteStudentConcession" src="<?php echo IMG_HTTP_PATH;?>/delete.gif"  />
                                     <b>&nbsp;Delete Concession</b></a></nobr>	</td>
                                     
                               </tr>
                             </table>     
                         </td>     
                    </tr>
				</table>
			</td>
			</tr>
            <tr id='nameRow' style='display:<?php echo $showData?>'>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Fee Head Details: </td>
						<td align="right" valign="middle">
                           
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id='resultRow2' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" >
                   <table width="5%" border="0" cellspacing="0" cellpadding="0" align="left"> 
                       <tr>
                          <td class="contenttab_row1" valign="top" ><b><nobr>Student Name</nobr></b></td>
                          <td class="contenttab_row1" valign="top" ><nobr><b>:</b></nobr></td>
                          <td class="contenttab_row1" valign="top" style="width:40px"><nobr><span id='sName'></span></nobr></td>
                          <td class="contenttab_row1" valign="top" ><b><nobr>Roll No.</nobr></b></td>
                          <td class="contenttab_row1" valign="top" ><nobr><b>:</b></nobr></td>
                          <td class="contenttab_row1" valign="top" style="width:40px"><nobr><span id='sRollNo'></span></nobr></td>
                          <td class="contenttab_row1" valign="top" ><b><nobr>Univ. No.</nobr></b></td>
                          <td class="contenttab_row1" valign="top" ><nobr><b>:</b></nobr></td>
                          <td class="contenttab_row1" valign="top" style="width:40px"><nobr><span id='uRollNo'></span></nobr></td>
                          <td class="contenttab_row1" valign="top" ><b><nobr>Reg. No.</nobr></b></td>
                          <td class="contenttab_row1" valign="top" ><nobr><b>:</b></nobr></td>
                          <td class="contenttab_row1" valign="top" style="width:40px"><nobr><span id='rRollNo'></span></nobr></td>
                       </tr>
                       <tr>   
                          <td class="contenttab_row1" valign="top" ><b><nobr>Father's Name</nobr></b></td>
                          <td class="contenttab_row1" valign="top" ><nobr><b>:</b></nobr></td>
                          <td class="contenttab_row1" valign="top" style="width:40px"><nobr><span id='fName'></span></nobr></td>
                       </tr> 
                   </table>  
                </td>
             </tr>
             <tr id='resultRow' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" >
                     <div id="scroll2" style="overflow:auto; height:280px; vertical-align:top;">
                         <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                         <table width='100%' cellspacing=0 border=0 cellpadding=0>
                         <tr align='right'>
                         	<td width='75%'></td>
                         	<td style="padding-top:6px" align='left' width="11%"><strong>Total Fees</strong></td>
                         	<td width=1% align='center'style="padding-top:6px"><strong>:</strong></td>
                         	<td width="13%"style="padding-top:6px"><span id='totalFee' style="padding-right:20px;" class='contenttab_row1'></span>
                         	<input type='hidden' name='feeValueHidden' id='feeValueHidden'>
                         	<input type='hidden' name='currentClassId' id='currentClassId'>
                         	
                         	</td>
                         </tr>
                          <tr align='right'>
                          	<td width='70%'></td>
                         	<td style="padding-top:6px" align='left'><strong>Discount</strong></td>
                         	<td width=1% align='center' style="padding-top:6px"><strong>:</strong></td>
                         	<td style="padding-top:6px"><span id='discountAmount' style="padding-right:20px;" class='contenttab_row1'></span>
                         		<input type='hidden' name='discountValueHidden' id='discountValueHidden'>
                         	</td>
                         </tr>
                         <tr align='right'>
                         	<td width='70%'></td>
                         	<td style="padding-top:6px" align='left'><strong>Payable Amount</strong></td>
                         	<td width=1% align='center' style="padding-top:6px"><strong>:</strong></td>
                         	<td style="padding-top:6px"><span id='payableAmount' style="padding-right:20px;" class='contenttab_row1'></span>
                         	</td>
                         </tr>
                         </table>
        
                     </div>
                </td>
             </tr>
           
             <tr id='resultRow1' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" ><nobr>
                   <b>Reason<?php echo REQUIRED_FIELD ?>&nbsp;:&nbsp;</b><br>
                   <textarea name="comments" class='inputbox' id="comments" style="width:350px" cols="45" rows="3" maxlength="1800" onkeyup="return ismaxlength(this)"></textarea>
                   </nobr>
                  <input type='hidden' name='studentId' id='studentId'>
                </td>
             </tr>
          </table>
        </td>
    </tr>
	<tr id='nameRow2' style='display:<?php echo $showPrint?>'>
        <td colspan='1' align='right' height="35" valign="bottom">
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return addAdhocConcession();return false;" />&nbsp;
          <!-- <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/> -->
        </td>
    </tr>
    </table>
</form> 
    </td>
    </tr>
    </table>
<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<!--Daily Attendance Help  Details  End -->    

 
    


