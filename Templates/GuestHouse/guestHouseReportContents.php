<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
           <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Guest House Report : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="searchForm" id="searchForm" onsubmit="return false;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                 <td>
                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                 <tr>
                  <td class="contenttab_internal_rows"><b>Arrival Date From</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <?php
                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                     echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                   ?>
                  </td>
                  <td class="contenttab_internal_rows"><b>To Date</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+30, date("Y"))));
                   ?>
                  </td>
                  <td class="contenttab_internal_rows"><b>Guest House</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <select name="guestHouseId" id="guestHouseId" class="inputbox" onchange="getRooms(this.value);">
                    <option value="-1">All</option>
                    <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getHostelName('',' WHERE hostelType='.GUEST_HOUSE_TYPE);
                    ?>
                   </select>
                  </td>
                  <td class="contenttab_internal_rows"><b>Room</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <select name="roomId" id="roomId" class="inputbox" style="width:120px;" onchange="vanishData(1);">
                    <option value="-1">All</option>
                   </select>
                  </td>
                 </tr>
                 <tr>
                  <td class="contenttab_internal_rows"><b>Budget Heads</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <select name="headId" id="headId" class="inputbox" style="width:160px;" onchange="vanishData(1);">
                    <option value="-1">All</option>
                    <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getBudgetHeadsData(' WHERE headTypeId='.GUEST_HOUSE);
                    ?>
                   </select>
                  </td>
                  <td class="contenttab_internal_rows"><b>Status</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <select name="allocationId" id="allocationId" class="inputbox" style="width:100px;" onchange="vanishData(1);">
                    <option value="-1">All</option>
                    <option value="0">Waiting</option>
                    <option value="1">Allocated</option>
                    <option value="2">Rejected</option>
                   </select>
                  </td>
                  <td class="contenttab_internal_rows"><b>Req. By</b></td>
                  <td class="padding">:</td>
                  <td class="padding" colspan="1">
                   <select name="requestId" id="requestId" class="inputbox" onchange="vanishData(1);">
                    <option value="-1">All</option>
                    <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getGuestHouseRequester('-1');
                    ?>
                   </select>
                  </td>
                  <td>
                   <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif"   onClick="return fetchReport();return false;" />
                  </td>
                 </tr>
                 </table>
                </form> 
                  <div id="results"></div>
                 </td>
                </tr>
                </table>  
             </td>
          </tr>
          <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr id="printTrId" style="display:none">
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
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