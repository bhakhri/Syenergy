<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Bus Masters&nbsp;&raquo;&nbsp;Transport Staff Master</td>
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
                        <td class="content_title">Transport Staff Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddTransportStuff',360,250);blankValues();return false;" />&nbsp;</td>
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
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddTransportStuff','Add Transport Staff'); ?>
<form name="AddTransportStuff" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="stuffName" id="stuffName" class="inputbox" />
        </nobr></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
          <input type="text" name="stuffCode" id="stuffCode" class="inputbox" />
        </nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Joining Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <?php 
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->datePicker('join1',date('Y-m-d'));
           ?>
        </nobr>
    </td>
  </tr> 
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>License No<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="dlNo" id="dlNo" class="inputbox" maxlength="15" />
        </nobr>
    </td>
  </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Licensing Authority<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="dlAuthority" id="dlAuthority" class="inputbox" maxlength="50" />   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Expiry Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <?php 
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->datePicker('dlExp1',date('Y-m-d'));
           ?>
        </nobr>
    </td>
  </tr> 
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <select name="stuffType" id="stuffType" class="selectfield">
         <option value="">Select</option>
           <?php 
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getTransportStuffTypeData();
           ?>
         </select>  
        </nobr>
    </td>
  </tr> 
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTransportStuff');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditTransportStuff','Edit Transport Staff'); ?>
<form name="EditTransportStuff" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="stuffId" id="stuffId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="stuffName" id="stuffName" class="inputbox" />
        </nobr></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
          <input type="text" name="stuffCode" id="stuffCode" class="inputbox" />
        </nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Joining Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <?php 
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->datePicker('join2',date('Y-m-d'));
           ?>
        </nobr>
    </td>
  </tr> 
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>License No<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="dlNo" id="dlNo" class="inputbox" maxlength="15" />
        </nobr>
    </td>
  </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Licensing Authority<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="dlAuthority" id="dlAuthority" class="inputbox" maxlength="50" />   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Expiry Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <?php 
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->datePicker('dlExp2',date('Y-m-d'));
           ?>
        </nobr>
    </td>
  </tr> 
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <select name="stuffType" id="stuffType" class="selectfield">
         <option value="">Select</option>
           <?php 
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getTransportStuffTypeData();
           ?>
         </select>  
        </nobr>
    </td>
  </tr> 
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditTransportStuff');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
<?php
// $History: listTransportStuffContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 30/06/09   Time: 17:45
//Updated in $/LeapCC/Templates/TransportStuff
//Corrected look and feel of masters which are detected during user
//documentation preparation
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:37
//Created in $/LeapCC/Templates/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:03
//Created in $/Leap/Source/Templates/TransportStuff
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:44
//Updated in $/SnS/Templates/TransportStuff
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:47
//Created in $/SnS/Templates/TransportStuff
//Created module Transport Stuff Master
?>