<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR DEGREE LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (13.06.2008 )
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
                                     <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddDegree',315,250);blankValues();return false;" />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddDegree','Add Degree'); ?>
<form name="AddDegree" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td  class="contenttab_internal_rows"><strong>Degree Name<?php echo REQUIRED_FIELD; ?></strong></td>
     <td class="padding">:</td>
      <td class="padding"><input type="text" id="degreeName" name="degreeName"  style="width:150px" class="inputbox" maxlength="64"/>
     </td>
   </tr>
   <tr>
     <td  class="contenttab_internal_rows"><strong>Degree Code<?php echo REQUIRED_FIELD; ?></strong></td>
     <td class="padding">:</td>
      <td class="padding">
      <input type="text" id="degreeCode" name="degreeCode"  style="width:150px" class="inputbox" maxlength="10"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><strong>Degree Abbr.<?php echo REQUIRED_FIELD; ?></strong></td>
    <td class="padding">:</td>
      <td class="padding">
     <input type="text" id="degreeAbbr" name="degreeAbbr" style="width:150px" class="inputbox" maxlength="10"/>
    </td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDegree');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditDegree','Edit Degree '); ?>
<form name="EditDegree" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="degreeId" id="degreeId" value="" />  
    <tr>
     <td  class="contenttab_internal_rows"><strong>Degree Name<?php echo REQUIRED_FIELD; ?></strong></td>
     <td class="padding">:</td>
      <td class="padding">
      <input type="text" id="degreeName" name="degreeName"  style="width:150px" class="inputbox" maxlength="64"/>
     </td>
   </tr>
   <tr>
     <td  class="contenttab_internal_rows"><strong>Degree Code<?php echo REQUIRED_FIELD; ?></strong></td>
     <td class="padding">:</td>
      <td class="padding">
      <input type="text" id="degreeCode" name="degreeCode"  style="width:150px" class="inputbox" maxlength="10"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><strong>Degree Abbr.<?php echo REQUIRED_FIELD; ?></strong></td>
    <td class="padding">:</td>
      <td class="padding">
     <input type="text" id="degreeAbbr" name="degreeAbbr" style="width:150px" class="inputbox" maxlength="10"/>
    </td>
   </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditDegree');return false;" />
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
// $History: listDegreeContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/28/09    Time: 1:27p
//Updated in $/LeapCC/Templates/Degree
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 31/07/09   Time: 14:38
//Updated in $/LeapCC/Templates/Degree
//Done bug fixing.
//bug ids---0000803,0000804
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Templates/Degree
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Degree
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Degree
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:22p
//Updated in $/Leap/Source/Templates/Degree
//Added functionality for quota report print
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Degree
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Degree
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/Degree
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Degree
//corrected breadcrumb and reset button height and width
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/Degree
//Solved TabOrder Problem
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Templates/Degree
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:59p
//Updated in $/Leap/Source/Templates/Degree
//Added AjaxList Functinality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:23p
//Updated in $/Leap/Source/Templates/Degree
//Adding AjaxEnabled Delete functionality
//
//***********Solved the problem :**********
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/18/08    Time: 2:38p
//Updated in $/Leap/Source/Templates/Degree
//Removing errors done
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Templates/Degree
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Degree
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:07a
//Created in $/Leap/Source/Templates/Degree
//Initial Checkin
?>