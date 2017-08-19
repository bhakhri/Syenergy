A<?php
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
                   <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"></td>
                 </tr>
                 <tr>
                    <td class="contenttab_row" colspan="2" valign="top" >
                      <table border="0" cellpadding="0" cellspacing="0">
                       <tr>
                        <td class="contenttab_internal_rows"><b>Class</td></td>
                        <td class="padding">:</td>
                        <td class="padding">
                         <select name="classId" id="classId" class="inputbox" style="width:250px" onchange="vanishData();"> 
                          <option value="-1">All</option>
                          <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getAdmApplClasssData();
                          ?>
                         </select>
                        </td>
                        <td>
                         <input type="image" name="search" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif"  onclick="fetchData();return false;" />
                        </td>
                       </tr> 
                      </table>
                      <div id="results"></div>
                    </td>
                 </tr>
                 <tr><td colspan="2" height="5px;"></td></tr>
                 <tr>
                  <td align="center" colspan="2" id="buttonDivId" style="display:none;">
                    <input type="image" name="search" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="saveData();return false;" />                   
                  </td>
                </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
  </table>