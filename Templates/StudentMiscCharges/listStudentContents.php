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
                              <table border="0" cellspacing="2px" cellpadding="2px" >
                                <tr>
                                    <td class="contenttab_internal_rows"><b>Fee Class</b>&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                    <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows">
                                        <select size="1" name="feeClassId" id="feeClassId" style="width:320px" class="selectfield" >
                                            <option value="">Select</option>
                                            <?php
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               echo HtmlFunctions::getInstance()->getAllFeeClass();
                                            ?>
                                        </select>
                                    </td>
                                    <td class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Reg. / Univ / Roll No.</b></nobr></td>
                                    <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows">
                                        <nobr><input type="text" id="rollNo" name="rollNo" class="inputbox" style="width:120px" maxlength="50"></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Student Name</b></nobr></td>
                                    <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows">
                                        <nobr><input type="text" id="studentName" name="studentName" style="width:150px"  class="inputbox" maxlength="50"></nobr>
                                    </td>
                                 </tr>
                                 <tr>   
                                    <td class="contenttab_internal_rows"><b>Fee Head</b>&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                    <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td class="contenttab_internal_rows" >
                                       <select size="1"  name="feeHead" id="feeHead" style="width:320px;" class="selectfield"  >
                                         <option value="">Select</option>
                                         <?php
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                            echo HtmlFunctions::getInstance()->getFeeHeadData('headName',' AND isVariable=1');
                                         ?>
                                       </select> 
                                    </td>   
                                    <td class="contenttab_internal_rows" colspan="3" style="padding-left:20px">
                                       <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                    </td> 
                                 </tr>
                                 <tr id='miscChargesHidden' style="display:none">
                                   <td class="contenttab_internal_rows"><b>Amount</b></td>
                                   <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                   <td class="contenttab_internal_rows" colspan="8">
                                      <table border="0" cellspacing="0px" cellpadding="0px" >  
                                       <tr>
                                         <td class="contenttab_internal_rows"><nobr>
                                           <input onkeyup="getMiscCharges();" type="text" id="miscCharges" name="miscCharges" class="inputbox" maxlength="10" />
                                         </nobr></td>
                                         <td class="contenttab_internal_rows" style="padding-left:15px;" align="left"><nobr><strong>Value</strong></nobr></td>
                                         <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                         <td class="contenttab_internal_rows" align="left"><nobr>  
                                           <input type="radio" name="amountCheck" id="amountCheck1" value="0" checked="checked" />&nbsp;Leave existing amount unchanged
                                           &nbsp;<input type="radio" name="amountCheck" id="amountCheck2" value="1" />&nbsp;Overwrite existing amount
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
                        <td class="content_title">Student Detail: </td>
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
             <tr id='resultRow' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" >
                     <div id="scroll2" style="overflow:auto; height:420px; vertical-align:top;">
                         <div id="results" style="width:98%; vertical-align:top;"></div>
                     </div>
             </td>
          </tr>
          </table>
        </td>
    </tr>
	<tr id='nameRow2' style='display:<?php echo $showPrint?>'>
        <td colspan='1' align='right' height="35" valign="bottom">
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return addStudentMiscCharges();return false;" />&nbsp;
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

 
    


