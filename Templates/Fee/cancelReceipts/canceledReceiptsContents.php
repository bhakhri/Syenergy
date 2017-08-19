<?php
//-------------------------------------------------------
// Purpose: to design the layout for payment history.
// Author : Nishu Bindal
// Created on : (08.May.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
  <form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" class="title">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                       <td valign="top">
                         <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
                       </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
           <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                  <td valign="top" class="content" align="center" width="100%"><a id="lk1" class="set_default_values">Set Default Values for Report Parameters</a>
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
		         <tr>
		            <td class="contenttab_border2" align="center" >
		                 <div style="height:15px"></div>  
                                 <?php require_once(TEMPLATES_PATH . "/listFeeReportContents.php");?> 
		            </td>
		         </tr>
                     </table>       
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
					 
                        <tr id='showTitle' style='display:none;'>
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                    <tr>
                                        <td colspan="1" class="content_title">Fee Receipt Status :</td>
                                        <td colspan="2" class="content_title" align="right">
                                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/>
                                        </td>
										
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id='showData' style='display:none;'>
                            <td  class='contenttab_row'>
                                   <div id="results" style="width:100%; vertical-align:top;"></div>
                            </td>
                        </tr>
                       <tr id='nameRow2' >
                           <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                    <tr>
                                        <td colspan="2" class="content_title" align="right">
                                           <div id = 'saveDiv' style="display:none">
                                              <input type="hidden" name="listSubject" value="1">
                                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/>
                                           </div>
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
   </td>
 </tr>
</table>  
</form>
