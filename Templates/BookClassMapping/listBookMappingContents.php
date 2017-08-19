<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                            </tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top">
                                <form name="searchForm2" id="searchForm2" onsubmit="return false;">
                                <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                 <td class=""><b>Time Table</b></td>
                                 <td class="padding">:</td>
                                 <td class="padding">
                                  <select name="timeTableId" id="timeTableId" class="inputbox" style="width:320px;" onchange="getClasses(this.vaue);vanishData();">
                                   <option value="">Select</option>
                                   <?php
                                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                     echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
                                   ?>
                                  </select>
                                 </td>
                                 <td class=""><b>Class</b></td>
                                 <td class="padding">:</td>
                                 <td class="padding">
                                  <select name="classId" id="classId" class="inputbox" style="width:320px;" onchange="vanishData();">
                                   <option value="">Select</option>
                                  </select>
                                 </td>
                                 <td class="padding">
                                   <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/show_list.gif" onClick="fetchData();" >
                                 </td>
                                </tr> 
                                </table>
                               </form> 
                                  <div id="results"></div>
                                 </td>
                          </tr>
                          <tr><td height="5px;"></td></tr>
                          <tr id="saveTrId" style="display:none;">
                           <td align="center">
                            <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/save.gif" onClick="validateForm();" >
                           </td>
                          </tr>
             <!--<tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>  
                        </tr>
                    </table>
                </td>
             </tr>
             -->
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>