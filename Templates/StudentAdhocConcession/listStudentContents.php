<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
				<table border='00' width='100%' cellspacing='0'>
                    <tr>
                         <td class="contenttab_internal_rows" colspan="15">
                              <table width="50%" border="0" cellspacing="2px" cellpadding="2px" >
                                <tr>
                                    <td class="contenttab_internal_rows"><nobr><b>Fee Class</b>&nbsp;<?php echo REQUIRED_FIELD ?></nobr></td>
                                    <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows"><nobr>
                                        <select size="1" name="feeClassId" id="feeClassId" style="width:300px" onchange="vanishData(); return false;" class="selectfield" >
                                            <option value="">Select</option>
                                            <?php
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               echo HtmlFunctions::getInstance()->getAllFeeClass();
                                            ?>
                                        </select></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" style="padding-left:2px"><nobr><b>Reg. / Univ / Roll No.</b></nobr></td>
                                    <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows">
                                        <nobr><input type="text" id="rollNo" name="rollNo" class="inputbox" style="width:120px" maxlength="50"></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" style="padding-left:2px"><nobr><b>Student Name</b></nobr></td>
                                    <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows">
                                        <nobr><input type="text" id="studentName" name="studentName" style="width:120px"  class="inputbox" maxlength="50"></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" style="padding-left:10px">
                                       <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                    </td> 
                                 </tr>
                                 <tr><td height="5px"></td></tr>
                                 <tr id='adhocChargesHidden' style="display:none">
                                   <td class="contenttab_internal_rows" colspan="5">
                                     <table border="0" cellspacing="0px" width="10%" cellpadding="0px" align="left"  >  
                                     <tr>
                                       <td class="contenttab_internal_rows"><nobr><b>Concession Amount</b></nobr></td>
                                       <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                       <td class="contenttab_internal_rows"><nobr>
                                           <input onkeyup="getAdhocCharges();" style="width:252px" type="text" id="adhocCharges" name="adhocCharges" class="inputbox" maxlength="10" />
                                         </nobr>
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
            <tr id='nameRow' style='display:<?php echo $showData?>'>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Fee Head Details: </td>
						<td align="right" valign="middle">
                           <!-- 
                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;
                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/>
                           -->    
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
                     </div>
                </td>
             </tr>
             <tr id='resultRow1' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" ><nobr>
                   <b>Reason<?php echo REQUIRED_FIELD ?>&nbsp;:&nbsp;</b><br>
                   <textarea name="comments" class='inputbox' id="comments" style="width:350px" cols="45" rows="3" maxlength="1800" onkeyup="return ismaxlength(this)"></textarea>
                   </nobr>
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

 
    


