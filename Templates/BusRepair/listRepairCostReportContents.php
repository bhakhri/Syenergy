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
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right"></td>
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
                        <td class="content_title">Bus Repair Cost Report : </td>
                        <td class="content_title">&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" style="padding:5px;" >
                <form name="allDetailsForm" method="post"  action="" onsubmit="return false;">
                 <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:7px;"><b>From</b>
                     <td class="padding" valign="top">:&nbsp;
                      <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('fromDate',date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-30, date('Y'))));
                      ?>
                     </td>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:7px;"><b>To</b>
                     <td class="padding" valign="top">:&nbsp;
                      <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                      ?>
                     </td>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:7px;"><b>Bus</b>
                     <td class="padding" valign="top" style="padding-top:7px;">:&nbsp;</td>
                     <td valign="top" style="padding-top:4px;">
                     <div id="containerDiv">
                      <select name="busId" id="busId" multiple="multiple" size="5" style="width:200px;" >
                        <?php
                          echo HtmlFunctions::getInstance()->getBusData();
                        ?>
                      </select>
                      <?php
                       $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
                       if($isIE6==1){
                       ?>    
                        <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("busId","All","allDetailsForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("busId","None","allDetailsForm");'>None</a>
                        <?php    
                        }
                        ?>
                      </div>
                      <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d1"></div>
                      <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d2" >
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                               <tr>
                                  <td id="d3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                                  <td width="5%">
                                  <img id="downArrawId" src="<?php echo IMG_HTTP_PATH ?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('busId','d1','containerDiv','d3');" />
                                  </td>
                                </tr>
                             </table>
                      </div>
                     </td>
                     <td valign="top" style="padding-top:4px;padding-left:5px;">
                      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false;" />
                     </td>
                 </tr>
                 </table>
                 <table>
                  <tr>
                  <td colspan="8">
                   <!--Main Div which will show data-->
                     <div id="flashcontent" style="width:950px;width:900px;overflow:auto;"></div>
                  <!--Main Div which will show data-->
                  </td>
                 </tr>  
                </table> 
                </form> 
                
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
<?php
// $History: listRepairCostReportContents.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 15/02/10   Time: 17:43
//Updated in $/LeapCC/Templates/BusRepair
//Modified javascript and html coding for "New Multiple Selected
//Dropdowns" as these are not working in IE6.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Templates/BusRepair
//Made UI changes
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/BusRepair
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/28/09    Time: 11:38a
//Updated in $/LeapCC/Templates/BusRepair
//Gurkeerat: resolved issue 1338
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Templates/BusRepair
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 11:46
//Created in $/Leap/Source/Templates/BusRepair
//Added new files for Bus Masters
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:40
//Updated in $/SnS/Templates/BusRepair
//Removed extra html code
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:16
//Created in $/SnS/Templates/BusRepair
//Added "Bus Repair Cost Report" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 10:55
//Created in $/SnS/Templates/Fuel
//Added "Fuel Uses Report" module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:36
//Updated in $/SnS/Templates/Fuel
//Enhanced fuel master
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:04
//Updated in $/SnS/Templates/Fuel
//Enhanced fuel master
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:50
//Updated in $/SnS/Templates/Fuel
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Templates/Fuel
//Created Fuel Master
?>