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
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
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
                        <td class="content_title">Insurance Due Report : </td>
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
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;"><b>From</b>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;">&nbsp;<b>:</b>&nbsp;</td>
                     <td class="padding" valign="top">
                      <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('fromDate',date("Y-m-d"));
                      ?>
                     </td>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;padding-left:10px;"><b>To</b>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;">&nbsp;<b>:</b>&nbsp;</td>
                     <td class="padding" valign="top">
                      <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('toDate',date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')+365, date('Y'))));
                      ?>
                     </td>
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;padding-left:10px;"><b>Bus</b>
                     <td class="padding" valign="top" style="padding-top:5px;">:&nbsp;</td>
                     <td valign="top">
                      <select name="busId" id="busId" multiple="multiple" size="5" >
                        <?php
                          echo HtmlFunctions::getInstance()->getBusData();
                        ?>
                      </select><br/>
                      Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("busId","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("busId","None");'>None</a>
                     </td>
                     <td valign="bottom" style="padding-top:10px;padding-left:5px;">
                      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false;" />
                     </td>
                 </tr>
                 </table>
                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                  <td colspan="8">
                   <!--Main Div which will show data-->
                     <div id="resultDiv"></div>
                  <!--Main Div which will show data-->
                  </td>
                 </tr>  
                </table> 
                </form> 
                
                </div>           
             </td>
          </tr>
          <tr><td height="10px"></td></tr>
          <tr>
              <td height="10px" align="right">
                   <input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;
                   <input type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
              </td>
          </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: listInsuranceDueReportContents.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 2/01/10    Time: 7:13p
//Updated in $/Leap/Source/Templates/Bus
//Add new report for insurance due report
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/09/09   Time: 18:11
//Updated in $/Leap/Source/Templates/Bus
//Done bug fixing found in self testing
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/08/09    Time: 10:30
//Updated in $/Leap/Source/Templates/Bus
//done bug fixing.
//bug ids---
//0000844,0000845,0000847,0000850,000843
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/06/09   Time: 11:15
//Updated in $/Leap/Source/Templates/Bus
//Done bug fixing.
//bug ids---0000063,0000082,0000083,0000085,0000087,0000090,0000092,
//0000095
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Templates/Bus
//Updated fleet mgmt file in Leap 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:26
//Created in $/SnS/Templates/Bus
//Added "InsuranceDue Report" module
?>