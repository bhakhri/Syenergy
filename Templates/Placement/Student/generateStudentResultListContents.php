<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
        <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Generate Student Result List : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="searchForm" action="" method="" style="display:inline" onsubmit="return false;" >
                   <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>Placement Drive</b></td>
                    <td class="padding" width="1%">:</td>
                    <td class="padding" width="2%">
                      <select name="placementDriveId" id="placementDriveId" class="inputbox" onchange="fetchPlacementDriveDetails(this.value);" style="width:350px;">
                       <option value="">Select</option>
                       <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         $conditions =' AND 
                                            (
                                              placementDriveId IN ( SELECT DISTINCT placementDriveId FROM placement_eligibility_list)
                                            )
                                      ';
                         echo HtmlFunctions::getInstance()->getPlacementDrives(' ',$conditions);
                       ?>
                      </select>
                    </td>
                    <td class="padding" align="left">
                       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" onClick="generateStudentList();" />
                    </td>
                   </tr>
                   <tr> 
                    <td colspan="4" align="left">
                     <div id="summeryDiv" style="width:52%;display:none;">
                     <fieldset>
                      <legend class="contenttab_internal_rows"><b>Placement Drive Details</b></legend>
                      <table border="0" cellpadding="0" cellspacing="0">
                           <tr>
                            <td class="contenttab_internal_rows"><b>Eligibility Criteria</b></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows" id="eleTdId"></td>
                            <td class="contenttab_internal_rows" style="padding-left:5px;"><b>Cutoff Marks</b></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows" id="eleTdId1"></td>
                            <td class="contenttab_internal_rows" id="eleTdId2"></td>
                            <td class="contenttab_internal_rows" id="eleTdId3"></td>
                            <td class="contenttab_internal_rows" id="eleTdId4"></td>
                           </tr>
                           <tr>
                            <td class="contenttab_internal_rows"><b>Test</b></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows" id="testTdId"></td>
                           </tr>
                           <tr>
                            <td class="contenttab_internal_rows"><b>Technical Interview</b></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows" id="interviewTdId"></td>
                           </tr>
						   <tr>
                            <td class="contenttab_internal_rows"><b>HR Interview</b></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows" id="interviewTdId1"></td>
                           </tr>
                           <tr>
                            <td class="contenttab_internal_rows"><b>Group Discussion</b></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows" id="gdTdId"></td>
                           </tr>
                      </table>
                      </fieldset>
                     </div>
                    </td> 
                   </tr>
                   <tr><td colspan="4" height="5px;"></td></tr>
                   <tr> 
                    <td colspan="4" align="left">
                     <div id="results"></div>
                    </td> 
                   </tr>
                   <tr><td colspan="4" height="5px"></td></tr>
                   <tr id="saveTRId" style="display:none;"> 
                    <td colspan="4" align="center">
                       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="doResultListing();" />&nbsp;
                       <input type="image" id="printIcon" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;
                       <input type="image" id="excelIcon" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printCSV();" />
                    </td> 
                   </tr>
                   </table>
                 </form>  
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
// $History: listEventContents.php $
?>