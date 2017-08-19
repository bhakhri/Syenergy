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
                <td valign="top">Admin Func.&nbsp;&raquo;&nbsp;Fleet Management&nbsp;&raquo;&nbsp;Bus Repair Master</td>
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
                        <td class="content_title">Bus Repair Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddBusRepair',670,250);blankValues();return false;" />&nbsp;</td>
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

<?php floatingDiv_Start('AddBusRepair','Add Bus Repair Details'); ?>
<form name="AddBusRepair" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bus Registration Number<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="busId" id="busId">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1'); //only active busses
              ?>
        </select></nobr></td>
        <td rowspan="8" valign="top">
          <?php
           echo HtmlFunctions::getInstance()->getBusRepairType('Add'); 
          ?>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="stuffId" id="stuffId">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTransportStuffData('','');
              ?>
        </select></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Date of Repair<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dated1',date('Y-m-d'));
              ?>
        </nobr>
    </td>
  </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Service Reason<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="serviceFor" id="serviceFor" class="inputbox" maxlength="50" />   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Cost<?php echo REQUIRED_FIELD; ?></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="cost" id="cost" class="inputbox" maxlength="10" style="width:70px;" />&nbsp;(in Rs)   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Workshop Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="workshopName" id="workshopName" class="inputbox" maxlength="50" />   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bill Number<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="billNumber" id="billNumber" class="inputbox" maxlength="20" />   
        </nobr>
    </td>
  </tr> 
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Comments&nbsp;</b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding"><nobr>
           <textarea name="comments" id="comments" cols="20" rows="2" class="inputbox" maxlength="100" onkeyup="return ismaxlength(this);"></textarea>
        </nobr>
    </td>
  </tr>
  
<tr>
    <td height="5px" colspan="4"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBusRepair');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="4"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditBusRepair','Edit Bus Repair Details'); ?>
<form name="EditBusRepair" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="repairId" id="repairId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bus Registration Number<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="busId" id="busId">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1'); //only active busses
              ?>
        </select></nobr></td>
        <td rowspan="8" valign="top">
          <?php
           echo HtmlFunctions::getInstance()->getBusRepairType('Edit'); 
          ?>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="stuffId" id="stuffId">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTransportStuffData('','');
              ?>
        </select></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Date of Repair<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dated2',date('Y-m-d'));
              ?>
        </nobr>
    </td>
  </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Service Reason<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="serviceFor" id="serviceFor" class="inputbox" maxlength="50" />   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Cost<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="cost" id="cost" class="inputbox" maxlength="10" style="width:70px;" />&nbsp;(Rs.)   
        </nobr>
    </td>
  </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Workshop Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="workshopName" id="workshopName" class="inputbox" maxlength="50" />   
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bill Number<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
           <input type="text" name="billNumber" id="billNumber" class="inputbox" maxlength="20" />   
        </nobr>
    </td>
  </tr> 
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Comments&nbsp;</b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding"><nobr>
           <textarea name="comments" id="comments" cols="20" rows="2" class="inputbox" maxlength="100" onkeyup="return ismaxlength(this);"></textarea>
        </nobr>
    </td>
  </tr>
  
<tr>
    <td height="5px" colspan="4"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBusRepair');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="4"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
<?php
// $History: listBusRepairContents.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/BusRepair
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Templates/BusRepair
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 16/05/09   Time: 11:16
//Updated in $/Leap/Source/Templates/BusRepair
//Done bug fixing.
//Bug ids : 1018 to 1024
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Templates/BusRepair
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/04/09   Time: 12:47
//Updated in $/Leap/Source/Templates/BusRepair
//Modified bus repair modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:03
//Created in $/Leap/Source/Templates/BusRepair
//Added Files for bus modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:24
//Updated in $/SnS/Templates/BusRepair
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:55
//Created in $/SnS/Templates/BusRepair
//Created Bus Repair Module
?>