<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR THOUGHTS LISTING 
//
// Author :Parveen Sharma
// Created on : (18.03.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Thoughts</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                 <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                  </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Thoughts Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddThoughts',405,220);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">
                </div>           
             </td>
          </tr>
          <tr><td height="10px"></td></tr>
          <tr>
           <td align="right">
             <input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif"  onclick="printReport();" /> 
          </td></tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddThoughts','Add Thoughts'); ?>
<form name="AddThoughts" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="20%" class="contenttab_internal_rows" valign="top"><nobr><?php echo REQUIRED_FIELD;?><b>Thoughts :&nbsp;</b></nobr></td>
        <td width="80%" class="padding" valign="top"><nobr>
        <textarea cols="45" rows="4" class="inputbox1" id="thought" name="thought" maxlength="255" onkeyup="return ismaxlength(this)">
        </textarea> 
        </nobr></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddThoughts');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditThoughts','Edit Thoughts'); ?>
<form name="EditThoughts" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="thoughtId" id="thoughtId" value="" />  
    <tr>
    <td width="20%" class="contenttab_internal_rows" valign="top"><nobr><?php echo REQUIRED_FIELD;?><b>Thoughts :&nbsp;</b></nobr></td>
        <td width="80%" class="padding" valign="top"><nobr>
        <textarea cols="45" rows="4" class="inputbox1" id="thought" name="thought" maxlength="255" onkeyup="return ismaxlength(this)">
        </textarea> 
        </nobr></td>
    </tr>    
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditThoughts');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->

<!--Start Topic  Div-->
<?php floatingDiv_Start('divThoughts','Brief Description '); ?>
<form name="ThoughtsForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="89%"><div id="thoughtInfo" style="overflow:auto; width:400px; height:200px" ></div></td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
    
<?php
// $History: listThoughtsContents.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/07/09    Time: 1:19p
//Updated in $/LeapCC/Templates/Thoughts
//thoughts maxlength settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:12a
//Created in $/LeapCC/Templates/Thoughts
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 6:31p
//Created in $/Leap/Source/Templates/Thoughts
//thoughts
//

?>