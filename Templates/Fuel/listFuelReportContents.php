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
                        <td class="content_title">Fuel Usage Report : </td>
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
                     <td valign="top" style="padding-right:15px">
                     <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                       <td class="contenttab_internal_rows" valign="top" style="padding-top:10px;"><b>From</b>
                       <td class="padding" valign="top">:&nbsp;
                          <?php
                             require_once(BL_PATH.'/HtmlFunctions.inc.php');
                             echo HtmlFunctions::getInstance()->datePicker('fromDate',date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-30, date('Y'))));
                          ?>
                         </td>
                      </tr>
                      <tr>   
                         <td class="contenttab_internal_rows" valign="top" style="padding-top:10px;"><b>To</b>
                         <td class="padding" valign="top">:&nbsp;
                          <?php
                             require_once(BL_PATH.'/HtmlFunctions.inc.php');
                             echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                          ?>
                         </td>
                       </tr>
                      </table>
                     <td>  
                     <td class="contenttab_internal_rows" valign="top" style="padding-top:10px;"><b>Bus</b>
                     <td class="padding" valign="top" style="padding-top:10px;">:&nbsp;</td>
                     <td valign="top" style="padding-top:2px;">
                      <select name="busId" id="busId" multiple="multiple" size="5" >
                        <?php
                          echo HtmlFunctions::getInstance()->getBusData();
                        ?>
                      </select><br/>
                      Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("busId","All");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("busId","None");'>None</a>
                     </td>
					 
					 <td valign="top">
						 <table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td class="contenttab_internal_rows" valign="top" style="padding-top:10px;"><b><input type="checkbox" id="fuelConsumed" name="fuelConsumed" value="1" checked="checked">Fuel Consumed</input>
							</tr>
							<tr>
								<td class="contenttab_internal_rows" valign="top" style="padding-top:10px;"><b><input type="checkbox" id="totalKM" name="totalKM" value="2" checked="checked">Total KM</input>
							</tr>
							<tr>
								<td class="contenttab_internal_rows" valign="top" style="padding-top:10px;"><b><input type="checkbox" id="fuelAverage" name="fuelAverage" value="3" checked="checked">Fuel Average</input>
							</tr>
						 </table>
					 </td>

                     <td valign="bottom" style="padding-bottom:11px;padding-left:5px;">
                      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false;" />
                     </td>
                     <td style="padding-left:20px;padding-bottom:11px;" valign="bottom">
                      <div id="summaryDiv" style="display:inline;width:510px;"></div>
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
// $History: listFuelReportContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/09/09   Time: 18:11
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing found in self testing
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 28/07/09   Time: 11:10
//Updated in $/Leap/Source/Templates/Fuel
//corrected button's position
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/07/09   Time: 19:00
//Updated in $/Leap/Source/Templates/Fuel
//Updated fuel usage report by adding "fuel usage average details"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 11:46
//Created in $/Leap/Source/Templates/Fuel
//Added new files for Bus Masters
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:19
//Updated in $/SnS/Templates/Fuel
//Modified label
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:39
//Updated in $/SnS/Templates/Fuel
//Removed Extra html code
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