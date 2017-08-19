<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
// Author :Nishu Bindal
// Created on : (22.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBusStopRouteMapping',320,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                   <!-- <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
                                            </td>
                    </tr>-->
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
<?php floatingDiv_Start('AddBusStopRouteMapping','Add Bus Stop Route Mapping'); ?>
<form name="AddBusStopRoute" action="" method="post" >  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
       <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: 
         <select size="1" class="selectfield" name="routeCode" id="routeCode" >
		 <option value="">select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->fetchBusRoutes('', '');
              ?>
        </select></nobr>
    </td>
  </tr>
     <tr>
        <td width="21%" class="contenttab_internal_rows" valign='top'><nobr><b>Vehicle Stop City<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding" valign='top'><nobr>:
         <select size="1" class="selectfield" name="stopCityName" id="stopCityName" onchange="getStopNames(this.value,'add','')">
		
        </select></nobr>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Stop Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
         <td width="79%" class="padding" valign='top'><nobr>:
         <select size="1" class="selectfield" name="stopName" id="stopName">
        </select></nobr>
    </tr>
<tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Scheduled Arival Time<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="scheduleTime" name="scheduleTime" class="inputbox" style="width:100px;"  />&nbsp;(HH:MM:SS)</nobr></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBusStopRouteMapping');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditBusStopRouteMapping','Edit Vehicle Stop'); ?>
<form name="EditBusStopRoute" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="busRouteStopMappingId" id="busRouteStopMappingId" value="" />
     <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: 
         <select size="1" class="selectfield" name="routeCode" id="routeCode" >
		 <option value="">select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->fetchBusRoutes('','');
              ?>
        </select></nobr>
    </td>
  </tr>
     <tr>
        <td width="21%" class="contenttab_internal_rows" valign='top'><nobr><b>Vehicle Stop City<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding" valign='top'><nobr>:
         <select size="1" class="selectfield" name="stopCityName" id="stopCityName" onchange="getStopNames(this.value,'edit','')">
		
        </select></nobr>
        </td>
 </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Stop Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
         <td width="79%" class="padding" valign='top'><nobr>:
         <select size="1" class="selectfield" name="stopName" id="stopName">
        </select></nobr>
    </tr>
  <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Scheduled Arival Time<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>: <input type="text" id="scheduleTime" name="scheduleTime" class="inputbox" style="width:100px;"  />&nbsp;(HH:MM:SS)</nobr></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBusStopRouteMapping');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->

