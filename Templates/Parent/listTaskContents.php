<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TRAINING
//
//
// Author :Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
							align="right" onClick="displayWindow('TaskActionDiv',350,250);blankValues();return false;" />&nbsp;</td>

 <!--<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
	
   <td valign="top" class="title"> 
         
           
            <tr>
         <td valign="top">Parent Activities&nbsp;&raquo;&nbsp;Task Manager </td> 
				<td valign="top" align="right"> 
                
         <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" /> 
                
     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="document.searchBox1.searchbox.value=document.searchBox1.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');
                    return false;"/> 
                 
              
          </tr>
            </table> -->
        </td> 
    </tr>
		    <table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
                 <td class="contenttab_row" valign="top">
                 <div id="TaskResultDiv"></div>
				</td>
			</tr>
			</table>
          </table>
        </td> 
    </tr>

	 
  </table>
<!--Start Add/Edit Div-->
<?php floatingDiv_Start('TaskActionDiv',''); ?>
 <form action="" method="" name="searchBox1">		
 <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" /> &nbsp;
  </form>
<form name="TaskDetail" action="" method="post">  
<input type="hidden" name="taskId" id="taskId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Title </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="2%" class="padding"><strong><nobr>:</nobr></strong></td>
      <td width="63%" class="padding">
      <input type="text" id="title" name="title" style="width:192px" class="inputbox" />
     </td>
	</tr>
	<tr>
	  <td class="contenttab_internal_rows" ><nobr>&nbsp;<strong>Short Description</strong><?php echo REQUIRED_FIELD; ?></nobr></td>	
      <td  class="padding"><strong><nobr>:</nobr></strong></td>
	   <td width="63%" class="padding">
        <textarea name="shortDesc" id="shortDesc" cols="22" rows="3" style="vertical-align:top;"></textarea>
     </td>
	 </tr>
	 <tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Task Date<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding"><strong><nobr>:</nobr></strong></td>
        <td class="padding" ><?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dueDate',date('Y-m-d'));
              ?>
      </td>
	</tr>
	<tr>
		<td width="35%" class="contenttab_internal_rows">&nbsp;<b>Reminder Option</b><?php echo REQUIRED_FIELD; ?></td>
        <td class="padding"><strong><nobr>:</nobr></strong></td>
		<td>
		  <input type="checkbox" id="dashboard" name="dashboard" checked="checked" />Dashboard
		  <input type="checkbox" id="sms" name="sms" />SMS
		</td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Prior Days<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td  class="padding"><strong><nobr>:</nobr></strong></td>
        <td class="padding" colspan="2"><input type="text" id="daysPrior" name="daysPrior" style="width:50px" class="inputbox" value="0" maxlength="2" />
      </td>
	</tr>

	<tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Status</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td  class="padding"><strong><nobr>:</nobr></strong></td>
      <td class="padding">
      <select id="status" name="status" class="selectfield1" style="width:100px">
		<option value="0" selected="selected">Pending</option>
		<option value="1" >Completed</option>
	  </select>
     </td>
	</tr>
    
  <tr>
    <td height="15px"></td>
  </tr>
 <tr>
    <td align="center"  colspan="5">
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
//*****************  Version 7  *****************
//User: Parveen      Date: 9/25/09    Time: 11:28a
//Updated in $/LeapCC/Templates/Parent
//alignment & formatting update (add)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Templates/Parent
//alignment & condition format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/24/09    Time: 10:26a
//Updated in $/LeapCC/Templates/Parent
//search condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Parent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/17/09    Time: 2:24p
//Updated in $/LeapCC/Templates/Parent
//validation, formatting, link tabs updated
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:30p
//Updated in $/LeapCC/Templates/Parent
//put the task files in parent module
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 4/09/09    Time: 4:24p
//Updated in $/SnS/Templates/Parent
//modified in templates 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/27/09    Time: 6:53p
//Updated in $/SnS/Templates/Parent
//fixed bugs
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/27/09    Time: 4:45p
//Updated in $/SnS/Templates/Parent
//put the class in reminder option
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/24/09    Time: 4:33p
//Updated in $/SnS/Templates/Parent
//modified in task for parent & student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:50p
//Created in $/SnS/Templates/Parent
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