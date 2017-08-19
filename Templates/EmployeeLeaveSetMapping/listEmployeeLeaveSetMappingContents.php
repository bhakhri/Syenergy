<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.1.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
//$leaveSetString=HtmlFunctions::getInstance()->getLeaveSetAdvData(' AND isActive=1');
$leaveSetString=HtmlFunctions::getInstance()->getLeaveSessionSetAdvData(' AND s.active=1 AND ls.isActive=1');
?>

<select name="leaveSetHidden" id="leaveSetHidden" style="display:none;">
 <?php
  echo $leaveSetString;
 ?>
</select>
<select name="leaveSessionId" id="leaveSessionId" class="inputbox" style='width:180px;display:none;' >
    <option value="">SELECT</option>
    <?php
      require_once(BL_PATH.'/HtmlFunctions.inc.php');
      echo HtmlFunctions::getInstance()->getLeaveSessionData();
    ?>
</select>

   <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddEmployeeLeaveSetMapping',355,250);blankValues();return false;" />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddEmployeeLeaveSetMapping','Add Employee Leave Set Mapping'); ?>
<form name="AddEmployeeLeaveSetMapping" action="" method="post">
<input type="hidden" name="employeeId" id="employeeId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4" >&nbsp;
     <input type="text" name="employeeCode" id="employeeCode" class="inputbox" onblur="getEmployeeCode(this.value);" />
     </td>
   </tr>
 <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4" >&nbsp;
     <div id="employeeName" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
<tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave Set<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4" >&nbsp;
       <select name="leaveSet" id="leaveSet" class="selectfield">
        <option value="">SELECT</option>
           <?php
            echo $leaveSetString;
           ?>
      </select>
     </td>
   </tr>

<!--<form name="AddEmployeeLeaveSetMapping" action="" method="post">
 <input type="hidden" name="employeeId" id="employeeId" value="" />
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;</td>     
     <td class="contenttab_internal_rows" width="5%">                  
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox" onblur="getEmployeeCode(this.value);" />
     </td>   
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
   <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;</td>  
   <td class="contenttab_internal_rows" width="5%"> 
      <div id="employeeName" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Set<?php echo REQUIRED_FIELD ?></strong></nobr> </td>
    <td class="contenttab_internal_rows" width="1%"> <b>:</b>&nbsp;</td> 
    <td class="contenttab_internal_rows" width="5%">  <select name="leaveSet" id="leaveSet" class="selectfield">
        <option value="">SELECT</option>
           <?php
            echo $leaveSetString;
           ?>
      </select>
     </td>
    </tr>
   <tr id="empSearchTrId">
    <td colspan="3">-->
</table>
      <table border="0" cellpadding="0" cellspacing="0" >
        <tr>
	
         <td valign="top" >
    <fieldset>
    <legend>Search employees by name</legend>
     <table border="0" cellpadding="0" cellspacing="0">
      <tr>
       <td class="contenttab_internal_rows" width="70%"><b>Enter Name :</b>
         <input type="text" name="empName" id="empName" class="inputbox" onkeydown="return sendKeys('empName',event);" />
       </td>
       <td class="padding" align="left">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="searchEmployees();return false;"/>
       </td>
      </tr>
      <tr>
       <td colspan="2" valign="top">
        <div id="empSearchDiv" style="max-height:140px;overflow:auto;vertical-align:top;"></div>
       </td>
      </tr>  
     </table>
    </fieldset> 
   <!-- </td>
   </tr>    -->
  <tr>
    <td height="5px" colspan="2"></td></tr>
 <tr>
    <td align="center" colspan="6">  
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />&nbsp;
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddEmployeeLeaveSetMapping');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
 </tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditEmployeeLeaveSetMapping','Edit Employee Leave Set Mapping '); ?>
<form name="EditEmployeeLeaveSetMapping" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="mappingId" id="mappingId" value="" />  
    
     <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Employee Code<?php echo REQUIRED_FIELD ?></strong></nobr> </td>
     <td width="1%" class="contenttab_internal_rows">
     <b>&nbsp;:</b>&nbsp; </td>
     <td class="contenttab_internal_rows" width="5%">
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox" disabled="disabled" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td width="1%" class="contenttab_internal_rows"><b>&nbsp;:</b>&nbsp; </td>
      <td width="5%" class="contenttab_internal_rows"><div id="employeeName2" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Set<?php echo REQUIRED_FIELD ?></strong></nobr></td>
    <td width="1%" class="contenttab_internal_rows"><b>&nbsp;:</b>&nbsp;</td>
     <td width="5%" class="contenttab_internal_rows"> <select name="leaveSet" id="leaveSet" class="selectfield" >
        <option value="">SELECT</option>
           <?php
            echo $leaveSetString;
           ?>
      </select>
     </td>
    </tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditEmployeeLeaveSetMapping');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>   
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->

<?php
// $History: listFeedbackAdvOptionsContents.php $
?>
