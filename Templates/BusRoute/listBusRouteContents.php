<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSROUTE LISTING 
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBusRoute',320,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddBusRoute','Add Bus Route'); ?>
<form name="AddBusRoute" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td width="2%" class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input style='width:198px' type="text" id="routeName" name="routeName" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="2%" class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input  style='width:198px' type="text" id="routeCode" name="routeCode" class="inputbox" maxlength="10" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Bus</b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"  valign="top"><b><nobr>
            <select name="busId" id="busId" class="inputbox1" style="width: 200px;" >
            <?php
                $condition=" WHERE vechicleCategoryId = 2";
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBusData('',$condition);
            ?>
            </select></nobr>
        </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBusRoute');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditBusRoute','Edit Bus Route'); ?>
<form name="EditBusRoute" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="busRouteId" id="busRouteId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input style='width:198px'  type="text" id="routeName" name="routeName" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input style='width:198px'  type="text" id="routeCode" name="routeCode" class="inputbox" maxlength="10" /></td>
    </tr>
	
    <tr>    
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Bus</b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"  valign="top"><b><nobr>
            <select name="busId" id="busId" class="inputbox1" style="width: 200px;" >
            <?php
                $condition=" WHERE vechicleCategoryId = 2";
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBusData('',$condition);
            ?>
            </select></nobr>
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBusRoute');return false;" />
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
// $History: listBusRouteContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Templates/BusRoute
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/21/09   Time: 6:50p
//Updated in $/LeapCC/Templates/BusRoute
//Fixed bug nos. 0001822, 0001823, 0001824, 0001847, 0001850, 0001825
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/14/09    Time: 6:36p
//Updated in $/LeapCC/Templates/BusRoute
//put route charges and check box 
//
//*****************  Version 2  *****************
//User: Administrator Date: 2/06/09    Time: 11:34
//Updated in $/LeapCC/Templates/BusRoute
//Done bug fixing.
//BugIds : 1167 to 1176,1185
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/BusRoute
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:54p
//Updated in $/Leap/Source/Templates/BusRoute
//Added functionality for bus route report print
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/BusRoute
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/BusRoute
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/BusRoute
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/BusRoute
//corrected breadcrumb and reset button height and width
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/BusRoute
//Solved TabOrder Problem
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:58p
//Updated in $/Leap/Source/Templates/BusRoute
//Added AjaxList Funtioality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:07p
//Updated in $/Leap/Source/Templates/BusRoute
//Created BusRoute Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:34p
//Created in $/Leap/Source/Templates/BusRoute
//Initial  Checkin
?>
