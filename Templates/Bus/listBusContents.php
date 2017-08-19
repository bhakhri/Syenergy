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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Bus Masters&nbsp;&raquo;&nbsp;Bus Master</td>
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
                        <td class="content_title">Bus Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddBusDiv',670,250);blankValues();return false;" />&nbsp;</td>
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
             <a href="javascript:void(0);" onClick="printReport()"><input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a>&nbsp;<input type="image" name="printBusSubmit" id='generateCSV' onClick="printCSV();return false;" value="printBusSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/>
          </td></tr> 
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>

    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddBusDiv','Add Bus Detail'); ?>
<form name="AddBus" id="AddBus" action="<?php echo HTTP_LIB_PATH;?>/Bus/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr>
       <td valign="top">
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bus Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busName" name="busName" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'busName',event);"/></nobr></td>
            </tr>
            <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Registration No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busNo" name="busNo" class="inputbox" maxlength="15" onkeydown="return sendKeys(1,'busNo',event);" /></nobr></td>
            </tr>
            <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busModel" name="busModel" class="inputbox" maxlength="15" onkeydown="return sendKeys(1,'busModel',event);"/></nobr></td>
            </tr>
            <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Purchase Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                 <?php
                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                   echo HtmlFunctions::getInstance()->datePicker('purchaseDate1',date('Y-m-d'));
                ?>
                </nobr></td>
            </tr>    
            </tr>
                <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bus Photo</b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="file" id="busPhoto" name="busPhoto" class="inputbox" /></nobr></td>
            </tr>
      </table>
    </td>  
    <td valign="top" style="padding-left:10px">
        <table border="0" cellpadding="0" cellspacing="0">
           <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Seating Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busCapacity" name="busCapacity" class="inputbox" maxlength="4" style="width:50px" onkeydown="return sendKeys(1,'busCapacity',event);"/></nobr></td>
            </tr>
            <tr>
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Manufacturing Year<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                 <select size="1" class="selectfield" name="manYear" id="manYear" style="width:100px;">
                      <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getAdmissionYear(date('Y')); //dont be confused with function name
                      ?>
                </select></nobr>
            </td>
          </tr>
           <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>In Service<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding" valign="bottom"><nobr>&nbsp;<b>:</b>
                 <input type="radio" id="isActive1" name="isActive" checked="checked"/>Yes &nbsp;
                 <input type="radio" id="isActive2" name="isActive"   />No &nbsp;</nobr>
                 </td>
            </tr>
           <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Insuring Company</b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busInsCompany" name="busInsCompany" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'busInsCompany',event);"/></nobr></td>
          </tr>
          <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Insurance Due Date</b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                 <?php
                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                   echo HtmlFunctions::getInstance()->datePicker('insuranceDate1');
                ?>
                <input type="checkbox" name="insuranceReminder" id="insuranceReminder"><span class="contenttab_internal_rows"><b>Ins. Reminder</b></span>
                </nobr></td>
            </tr>
     </table>
     </td>      
   </tr>      
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBusDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditBusDiv','Edit Bus Detail'); ?>
<form name="EditBus" id="EditBus" action="<?php echo HTTP_LIB_PATH;?>/Bus/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="busId" id="busId" value="" />
     <tr>
       <td valign="top">
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bus Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busName" name="busName" class="inputbox" maxlength="30" onkeydown="return sendKeys(2,'busName',event);"/></nobr></td>
            </tr>
            <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Registration No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busNo" name="busNo" class="inputbox" maxlength="15" onkeydown="return sendKeys(2,'busNo',event);"/></nobr></td>
            </tr>
            <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busModel" name="busModel" class="inputbox" maxlength="15" onkeydown="return sendKeys(2,'busModel',event);"/></nobr></td>
            </tr>
            <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Purchase Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                 <?php
                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                   echo HtmlFunctions::getInstance()->datePicker('purchaseDate2',date('Y-m-d'));
                ?>
                </nobr></td>
            </tr>    
            </tr>
                <tr>    
                <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Bus Photo</b></nobr></td>
                <td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
                <input type="file" id="busPhoto" name="busPhoto" class="inputbox" /></nobr></td>
            </tr>
      </table>
    </td>  
    <td valign="top" style="padding-left:10px">
        <table border="0" cellpadding="0" cellspacing="0">
           <tr>    
                <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Seating Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td class="padding"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busCapacity" name="busCapacity" class="inputbox" maxlength="4" style="width:50px" onkeydown="return sendKeys(2,'busCapacity',event);"/></nobr></td>
                <td valign="bottom" rowspan="3" align="left">
                 <div id="busImageDiv"></div>
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Manufacturing Year<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td class="padding"><nobr>&nbsp;<b>:</b>
                 <select size="1" class="selectfield" name="manYear" id="manYear" style="width:100px;">
                      <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getAdmissionYear(date('Y')); //dont be confused with function name
                      ?>
                </select></nobr>
            </td>
          </tr>
           <tr>    
                <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>In Service<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                <td class="padding" valign="bottom"><nobr>&nbsp;<b>:</b>
                 <input type="radio" id="isActive3" name="isActive" checked="checked"/>Yes &nbsp;
                 <input type="radio" id="isActive4" name="isActive"   />No &nbsp;</nobr>
                 </td>
            </tr>
           <tr>    
                <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Insuring Company</b></nobr></td>
                <td class="padding" colspan="2"><nobr>&nbsp;<b>:</b>
                <input type="text" id="busInsCompany" name="busInsCompany" class="inputbox" maxlength="30" style="width:120px;" onkeydown="return sendKeys(2,'busInsCompany',event);"/></nobr></td>
          </tr>
          <tr>    
                <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Insurance Due Date</b></nobr></td>
                <td class="padding" colspan="2"><nobr>&nbsp;<b>:</b>
                 <?php
                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                   echo HtmlFunctions::getInstance()->datePicker('insuranceDate2');
                ?>
                <input type="checkbox" name="insuranceReminder" id="insuranceReminder"><span class="contenttab_internal_rows"><b>Ins. Reminder</b></span>
                </nobr></td>
            </tr>
     </table>
     </td>      
   </tr>
<tr><td height="5px" colspan="2"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBusDiv');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listBusContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Templates/Bus
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/07/09   Time: 10:26
//Updated in $/LeapCC/Templates/Bus
//Done bug fixing.
//bug ids---
//0000551,0000552
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 10/07/09   Time: 17:27
//Updated in $/LeapCC/Templates/Bus
//Done bug fixes.
//bug ids---0000538 to 0000543
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Templates/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Templates/Bus
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:14
//Updated in $/Leap/Source/Templates/Bus
//corrected popup div's  width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/04/09   Time: 12:38
//Updated in $/Leap/Source/Templates/Bus
//Corrected
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/04/09    Time: 11:10
//Updated in $/SnS/Templates/Bus
//Done bug fixing
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:37
//Updated in $/SnS/Templates/Bus
//Enhanced bus master module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/04/09    Time: 15:01
//Updated in $/SnS/Templates/Bus
//Added functions for displaying list view
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:15
//Updated in $/SnS/Templates/Bus
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:13
//Created in $/SnS/Templates/Bus
?>