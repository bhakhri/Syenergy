<?php
//-------------------------------------------------------
// Purpose: to design the layout for payment history.
// Author : Nishu Bindal
// Created on : (08.May.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
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
              <table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
                <tr>
                  <td valign="top" class="content" align="center">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			<td class="contenttab_border2" align="center">
			     <div style="height:15px"></div>  
                      <table border="0" cellspacing="0" cellpadding="0px" width="10%" align="center">                     
			       <tr align="center">			        
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" style="padding-left:10px;"><nobr><b>Date</b></nobr></td>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
			           
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr>From
			           	 <?php
		                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
		                    echo HtmlFunctions::getInstance()->datePicker('fromDate','');
		                ?>
			                &nbsp;To
				          <?php
		                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
		                    echo HtmlFunctions::getInstance()->datePicker('toDate','');
		                ?> 
			         	</nobr>
			              </td>
			              <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" style="padding-left:10px;"><nobr><b>Status</b></nobr></td>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
			            
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr>    
				         <select name="searchRegistrationStatus" id="searchRegistrationStatus" class="inputbox" style="width:180px;" >
			   				<option value="">All</option>
			   				<option value="0">Apply</option>
			   				<option value="1">Cancel</option>
			   				<option value="2">Approve</option>
			   				<option value="3">Reject</option>
			   				<option value="4">Pending</option>
			   			  </select>
		   				</nobr>
			              </td> 			       
						   <td class="contenttab_internal_rows" nowrap style="padding-left:20px;">  
						     <center>
						       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm('allDetailsForm');  return false;" />  
						     </center> 
						   <td>
						</tr>
						<tr><td height='10px'></td></tr>
						</table>
		    	   </td>
	              </tr>
                 </table>       
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr id='showTitle' style='display:none;'>
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                    <tr>
                                        <td colspan="1" class="content_title">Approve/ Unapprove Hostel Registration:</td>
                                        <td colspan="2" class="content_title" align="right">
                                        	  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/save.gif" onClick="approveHostelRegistration();return false;"/>&nbsp;
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
                                             <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/save.gif" onClick="approveHostelRegistration();return false;"/>&nbsp;
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
