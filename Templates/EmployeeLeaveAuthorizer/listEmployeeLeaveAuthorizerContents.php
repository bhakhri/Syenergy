<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.1.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $sessionHandler;
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');     
    if($leaveAuthorizersId=='') {
      $leaveAuthorizersId=1;  
    }
    
    $style = " style = 'display:none;'";
    if ($menuCreationManager->showSearch(MODULE) == true) {
        $style = " style = 'display:;'";
    }

    if($specialSearchCondition==''){
        $formSearchCondition="sendReq(listURL,divResultName,searchFormName,'')";
    }
    else{
        $formSearchCondition=$specialSearchCondition; 
    }

?>
    <input type="hidden" readonly="readonly" id="hiddenLeaveAuthorizersId" name="hiddenLeaveAuthorizersId" value="<?php echo $leaveAuthorizersId; ?>" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
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
<form id="searchForm" name="searchForm" onSubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; refereshLeaveAuthorizerList(); return false;" <?php echo $style;?> >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
    <?php
     if(UtilityManager::isIEBrowser()==0){//for FF
    ?>
        <tr height="30">
            <td width="19%" align="left"><input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-left:5px;margin-top: 2px;" size="30" /></td>
            <td align="left">
                <img  src="<?php echo IMG_HTTP_PATH;?>/search1.gif" style="margin-right: 5px;margin-top:2px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; refereshLeaveAuthorizerList(); return false;"/>
                <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
            </td>
        </tr>
   <?php
    }
    else //for IE
    {
    ?>
  <tr height="30">
            <td width="19%" align="left"><input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-left:5px;height:16px;" size="30" /></td>
            <td align="left">
                <img src="<?php echo IMG_HTTP_PATH;?>/search1.gif" style="margin-right:5px;margin-top:4px;height:20px;border:0px" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; refereshLeaveAuthorizerList(); return false;"/>
                <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
            </td>
        </tr>
    <?php
    }
    ?>

    </table>
</form>									
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddEmployeeLeaveAuthorizer',355,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="30">
                    <tr>
                    <td align="right" >
                  <input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport();"></a>&nbsp;
                  <input type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="printCSV();">
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
<?php floatingDiv_Start('AddEmployeeLeaveAuthorizer','Add Employee Leave Authorizer'); ?>
<form name="AddEmployeeLeaveAuthorizer" action="" method="post">
 <input type="hidden" name="employeeId" id="employeeId" value="" />
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code<?php echo REQUIRED_FIELD ?></b></nobr>
 </td>
 <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;</td>
 <td class="contenttab_internal_rows" width="5%" valign="left">
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox" onblur="getEmployeeCode(this.value);" style='width:240px;'/>
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr>
     </td>
     
  <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp; </td>
  <td class="contenttab_internal_rows" width="5%" valign="left"> 
      <div id="employeeName" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>First Authorizer<?php echo REQUIRED_FIELD ?></strong></nobr>
     </td>
    
    <td class="contenttab_internal_rows" width="1%"> <b>:</b>&nbsp;  </td>
    <td class="contenttab_internal_rows" width="5%" valign="left">    
      <select name="firstEmployee" id="firstEmployee" class="selectfield" style='width:240px;'>
        <option value="">SELECT</option>
      </select>
     </td>
    </tr>
   <?php if($leaveAuthorizersId==2) {  ?>
       <tr>
         <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Second Authorizer<?php echo REQUIRED_FIELD ?></strong></nobr>
        </td>
        <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;</td>  
        <td class="contenttab_internal_rows" width="5%">
          <select name="secondEmployee" id="secondEmployee" class="selectfield" style='width:240px;'>
            <option value="">SELECT</option>
          </select>
         </td>
        </tr> 
   <?php } ?>        
   <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type<?php echo REQUIRED_FIELD ?></strong></nobr>
    </td>  
    <td class="contenttab_internal_rows" width="1%">
     <b>:</b>&nbsp;     </td>
     <td class="contenttab_internal_rows" width="5%"> <select name="leaveType" id="leaveType" class="selectfield" style='width:240px;'>
        <option value="">SELECT</option>
      </select>
     </td>
    </tr>
   
  <tr><td height="5px" colspan="2"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddEmployeeLeaveAuthorizer');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
 </tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditEmployeeLeaveAuthorizer','Edit Employee Leave Authorizer '); ?>
<form name="EditEmployeeLeaveAuthorizer" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="mappingId" id="mappingId" value="" />  
     <tr>
     <td width="25%" class="contenttab_internal_rows">&nbsp;<nobr><nobr><strong>Employee Code<?php echo REQUIRED_FIELD ?></strong></nobr>
     </td><td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;                  </td>
     <td class="contenttab_internal_rows" width="5%"> <input type="text" name="employeeCode" id="employeeCode" class="inputbox" disabled="disabled" style='width:240px;'/>
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp; </td>
     <td class="contenttab_internal_rows" width="5%"> <div id="employeeName2" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>First Authorizer<?php echo REQUIRED_FIELD ?></strong></nobr>
     </td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;</td>
      <td class="contenttab_internal_rows" width="5%"><select name="firstEmployee" id="firstEmployee" class="selectfield" style='width:240px;'>
        <option value="">SELECT</option>
      </select>
     </td>
    </tr>
    <?php if($leaveAuthorizersId==2) {  ?>
       <tr>
         <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Second Authorizer<?php echo REQUIRED_FIELD ?></strong></nobr>
         </td>
         <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;    </td>
          <td class="contenttab_internal_rows" width="5%">
          <select name="secondEmployee" id="secondEmployee" class="selectfield" style='width:240px;'>
            <option value="">SELECT</option>
          </select>
         </td>
        </tr>
    <?php } ?>           
    <tr>
     <td width="5%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type<?php echo REQUIRED_FIELD ?></strong></nobr>  </td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b>&nbsp;</td>
      <td class="contenttab_internal_rows" width="5%"><select name="leaveType" id="leaveType" class="selectfield" style='width:240px;'>
        <option value="">SELECT</option>
      </select>
     </td>
    </tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditEmployeeLeaveAuthorizer');return false;" />
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