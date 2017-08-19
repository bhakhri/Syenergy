<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBusStop',320,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddBusStop','Add Bus Stop'); ?>
<form name="AddBusStop" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Stop Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="stopName" name="stopName" class="inputbox" maxlength="30" /></nobr></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Stop Abbr<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="stopAbbr" name="stopAbbr" class="inputbox" maxlength="10" /></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>:
         <select size="1" class="selectfield" name="routeCode" id="routeCode" onblur="autoCharges(this.value);">
		 <option value="">select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBusRouteName();
              ?>
        </select></nobr>
    </td>
  </tr>
   <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Schedule Time<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="scheduleTime" name="scheduleTime" class="inputbox" style="width:100px;"  />&nbsp;(HH:MM:SS)</nobr></td>
    </tr>
   <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Transport Charges<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="transportCharges" name="transportCharges" class="inputbox" style="width:100px;" maxlength="6"  /></nobr></td>
    </tr>
  
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBusStop');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditBusStop','Edit Bus Stop'); ?>
<form name="EditBusStop" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="busStopId" id="busStopId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Stop Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="stopName" name="stopName" class="inputbox" maxlength="30" /></nobr></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Stop Abbr<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="stopAbbr" name="stopAbbr" class="inputbox" maxlength="10" /></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: 
         <select size="1" class="selectfield" name="routeCode" id="routeCode" onblur="autoEditCharges(this.value)">
		 <option value="">select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBusRouteName();
              ?>
        </select></nobr>
    </td>
  </tr>
   <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Schedule Time<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="scheduleTime" name="scheduleTime" class="inputbox" style="width:100px;"  />&nbsp;(HH:MM:SS)</nobr></td>
    </tr>
   <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Transport Charges<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="transportCharges" name="transportCharges" class="inputbox" style="width:100px;" maxlength="6" /></nobr></td>
    </tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBusStop');return false;" />
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
// $History: listBusStopContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Templates/BusStop
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/14/09    Time: 6:36p
//Updated in $/LeapCC/Templates/BusStop
//put route charges and check box 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 30/06/09   Time: 17:45
//Updated in $/LeapCC/Templates/BusStop
//Corrected look and feel of masters which are detected during user
//documentation preparation
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 13:05
//Updated in $/LeapCC/Templates/BusStop
//Done bug fixing.
//bug ids--Issues[03-june-09].doc(1 to 11)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/BusStop
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:44p
//Updated in $/Leap/Source/Templates/BusStop
//Added functionality for bus stop report print
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/24/08    Time: 10:21a
//Updated in $/Leap/Source/Templates/BusStop
//Added functionilty for busRouteId in bus stop master
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/BusStop
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/BusStop
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/BusStop
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/BusStop
//corrected breadcrumb and reset button height and width
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/05/08    Time: 1:01p
//Updated in $/Leap/Source/Templates/BusStop
//Modifies" instituId"  insertion so that it comes from session variable
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/BusStop
//Solved TabOrder Problem
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:33p
//Updated in $/Leap/Source/Templates/BusStop
//Added AjaxListing Functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:06p
//Updated in $/Leap/Source/Templates/BusStop
//Modifying Page Title
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:29p
//Updated in $/Leap/Source/Templates/BusStop
//Created BusStop Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 4:02p
//Created in $/Leap/Source/Templates/BusStop
//Initial Checkin
?>