<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR DEPARTMENT LISTING 
//
//
// Author :Jaineesh
// Created on : (20.11.2008 )
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddDepartment',315,250);blankValues();return false;" />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddDepartment','Add Department'); ?>
<form name="addDepartment" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td width="40%" class="contenttab_internal_rows"><strong><nobr>Department Name<?php echo REQUIRED_FIELD;?></nobr></strong></td>
     <td  class="padding">:</td>
     <td class="padding"> <input type="text" id="departmentName" name="departmentName"  style="width:150px" class="inputbox" maxlength="100"/>
     </td>
   </tr>
   <tr>
     <td width="31%" class="contenttab_internal_rows"><strong>Abbr.<?php echo REQUIRED_FIELD;?></strong></td>
     <td class="padding">:</td>
      <td  class="padding"><input type="text" id="abbr" name="abbr"  style="width:150px" class="inputbox" maxlength="20"/>
     </td>
   </tr>
   <tr>
     <td width="40%" class="contenttab_internal_rows"><strong><nobr>Description</nobr></strong></td>
     <td  class="padding">:</td>
     <td class="padding"> <input type="text" id="description" name="description"  style="width:150px" class="inputbox" maxlength="150"/>
     </td>
   </tr>
   
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDepartment');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditDepartment','Edit Department '); ?>
<form name="editDepartment" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="departmentId" id="departmentId" value="" />  
    <tr>
     <td width="40%" class="contenttab_internal_rows"><strong>Department Name<?php echo REQUIRED_FIELD; ?></strong></td>
     <td  class="padding">:</td>
      <td  class="padding"><input type="text" id="departmentName" name="departmentName"  style="width:150px" class="inputbox" maxlength="100"/>
     </td>
   </tr>
   <tr>
     <td width="31%" class="contenttab_internal_rows"><strong>Abbr.<?php echo REQUIRED_FIELD; ?></strong></td>
     <td  class="padding">:</td>
      <td  class="padding"><input type="text" id="abbr" name="abbr"  style="width:150px" class="inputbox" maxlength="20"/>
     </td>
   </tr>
 <tr>
     <td width="40%" class="contenttab_internal_rows"><strong>Description </strong></td>
     <td  class="padding">:</td>
      <td  class="padding"><input type="text" id="description" name="description"  style="width:150px" class="inputbox" maxlength="100"/>
     </td>
   </tr> 
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditDepartment');return false;" />
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
// $History: listDepartmentContents.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/27/09    Time: 2:21p
//Updated in $/LeapCC/Templates/Department
//Gurkeerat: resolved issue 1293
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/Department
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:49
//Updated in $/LeapCC/Templates/Department
//Bug fixing.
//bug ids---
//00000256,00000257,00000259,00000261,00000263,00000264.
//00000266,00000269,00000262
//
//*****************  Version 4  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Department
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/02/09    Time: 3:23p
//Updated in $/LeapCC/Templates/Department
//put required field
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/28/09    Time: 11:07a
//Updated in $/LeapCC/Templates/Department
//put sendReq function 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Department
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:53p
//Created in $/Leap/Source/Templates/Department
//show the template
//

?>