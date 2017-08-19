<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
 <form name="allDetailsForm" id="allDetailsForm" action="" method="post">   
<?php 
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="15px"></td></tr>
                <tr>
                   <td class="contenttab_border1" colspan="3" valign="top" style="padding-left:10px" >  
                     <span class="contenttab_internal_rows"> 
                       <nobr>
                         <b><a href="javascript:void(0);" style="cursor:pointer;" onclick="getShowDetail();" class="link">
                            <label id="lblMsg">Please Click to Show Advance Search</label>
                            </a>
                         </b>
                          <img id="showInfo" style="cursor:pointer;" src="<?php echo IMG_HTTP_PATH; ?>/arrow-down.gif" onclick="getShowDetail(); return false;">
                       </nobr>
                     </span>
                   </td>   
<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>				   
                </tr>
                <tr id="showhideSeats" style="display:none">
                    <td class="contenttab_border1" colspan="3" valign="top" >
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="contenttab_border2" align="center">
                                <div style="height:15px"></div>  
                                <?php require_once(TEMPLATES_PATH . "/listFeeFineReportContents.php");?>   
                            </td>
                        </tr>  
                        </table>
                    </td>     
                </tr>   
                <tr height="30" id='nameRow1' style='display:none'>
					<td class="contenttab_border" height="20" style="border-right:0px;">
					  <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                            <tr>
                                <td colspan="1" class="content_title"> Student Fine Detail :</td>
                                <td colspan="2" class="content_title" align="right">
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
                                </td>
                            </tr>
                        </table>
					</td>
				</tr>
                <tr id='nameRow2' style='display:none'>
					<td class="contenttab_row" colspan="3" valign="top" >
						<div id="results"></div></td>
						</tr>
                      <tr>
                            <td align="center" colspan="3" style="display:none" id='printRow2' class="contenttab_internal_rows">
                              <center> <b>Please click to Show List Button</b></center>
                            </td>
                </tr>   
		        <tr id='nameRow3' style='display:none'>  
                        <td align="right" colspan="3" id='printRow'  >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                            <tr>
                                       <td class="content_title" valign="middle" align="right" width="20%">
		                              
                                      <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport(); return false;" >&nbsp;
                                      <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV(); return false;" >
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
    </form>  
   

<?php floatingDiv_Start('divMessage','Student Fine Details','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="8px"></td></tr>
    <tr>
    <tr>
        <td class="contenttab_internal_rows" style="padding-left:4px">
	   <b><span id="searchStudentFineList"></span></b>
        </td>
    </tr>
    <tr>
    <tr>
        <td height="7px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll2" style="overflow:auto; width:650px; height:450px; vertical-align:top;">
                <div id="message" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
