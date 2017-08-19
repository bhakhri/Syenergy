<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TRAINING
//
//
// Author :Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
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
							<td  class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
							align="right" onClick="displayWindow('TaskActionDiv',350,250);blankValues();return false;" />&nbsp;</td></tr>
  <!--  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
			</form> 
            </table>
        </td>
    </tr> -->
	

 <tr>
      <!--  <td valign="top"colspan=2>
         <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405"> 
             <tr> 
        <td valign="top" class="content"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0" > -->
				
				<tr> 
                 <td class="contenttab_row" valign="top" colspan=2>
                 <div id="TaskResultDiv"></div>
				</td>
			</tr>
          </table>
      <!--  </td>
    </tr> -->
  </table> 

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('TaskActionDiv',''); ?>
<form name="TaskDetail" action="" method="post">  
<input type="hidden" name="taskId" id="taskId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Title </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:&nbsp;
      <input type="text" id="title" name="title" style="width:170px" class="inputbox" />
     </td>
	</tr>
	<tr>
	  <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Short Description</strong><?php echo REQUIRED_FIELD; ?></nobr></td>	
	  <td align="top"><b><nobr>&nbsp;:&nbsp;&nbsp;</b><textarea name="shortDesc" id="shortDesc" cols="20" rows="3" style="vertical-align:top;"></textarea></nobr></td>
	 </tr>
	 <tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Task Date<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding" colspan="2">:&nbsp;&nbsp;<?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dueDate',date('Y-m-d'));
              ?>
      </td>
	</tr>
	<tr>
		<td width="35%" class="contenttab_internal_rows">&nbsp;<b>Reminder Option</b><?php echo REQUIRED_FIELD; ?></td>
		<td width="65%"><b>&nbsp;:</b>&nbsp;
		  <input type="checkbox" id="dashboard" name="dashboard" checked="checked" />Dashboard
		  <input type="checkbox" id="sms" name="sms" />SMS
		</td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Prior Days<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding" colspan="2">:&nbsp;&nbsp;<input type="text" id="daysPrior" name="daysPrior" style="width:100px" class="inputbox" value="0" maxlength="2" />
      </td>
	</tr>

	<tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Status</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td class="padding">:&nbsp;
      <select id="status" name="status" class="selectfield1" >
		<option value="0" selected="selected">Pending</option>
		<option value="1" >Completed</option>
	  </select>
     </td>
	</tr>
    
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('TaskActionDiv');if(flag==true){getTask();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<?php
// $History: listTaskContents.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 9/11/09    Time: 5:46p
//Updated in $/LeapCC/Templates/Student
//resolved issue 1497
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:43p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 4/09/09    Time: 10:12a
//Updated in $/SnS/Templates/Student
//modified in design template
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 4/01/09    Time: 3:45p
//Updated in $/SnS/Templates/Student
//modified for change status click on dashboard in teacher & parent
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/27/09    Time: 6:53p
//Updated in $/SnS/Templates/Student
//fixed bugs
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/27/09    Time: 4:45p
//Updated in $/SnS/Templates/Student
//put the class in reminder option
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/24/09    Time: 4:33p
//Updated in $/SnS/Templates/Student
//modified in task for parent & student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:10p
//Updated in $/SnS/Templates/Student
//modified for task
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/20/09    Time: 11:05a
//Created in $/SnS/Templates/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Templates/Task
//add new room if hostel room is different
//new task module in student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:41p
//Updated in $/SnS/Templates/Task
//modified in showing colon
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:35p
//Updated in $/SnS/Templates/Task
//modified in task template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:24p
//Created in $/SnS/Templates/Task
//new template for task
//
 
?>