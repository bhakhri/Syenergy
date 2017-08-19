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
              <table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
                <tr>
                  <td valign="top" class="content" align="center">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			<td class="contenttab_border2" align="center">
			     <div style="height:15px"></div>  
                      <table border="0" cellspacing="0" cellpadding="0px" width="10%" align="center">
                      	<tr>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>Roll No./Reg No.</b></nobr></td>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
			           <td  class="contenttab_internal_rows" nowrap width="2%">  
			             <input name="rollNo" id="rollNo" class="inputbox" style="width:220px" type="text">  
			           </td>
			           <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Student Name</b></nobr></td>
			           <td class="contenttab_internal_rows" nowrap valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
			           <td  class="contenttab_internal_rows" nowrap valgin="top"  width="2%"> 
			             <input name="studentName" id="studentName" class="inputbox" style="width:210px" type="text">  
			           </td>
			           <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Father's Name</b></nobr></td>
			           <td class="contenttab_internal_rows" nowrap valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
			           <td  class="contenttab_internal_rows"  nowrap valgin="top" width="2%"> 
			             <input name="fatherName" id="fatherName" class="inputbox" style="width:200px" type="text">  
			           </td>
			       </tr> 
			       <tr><td height='10px'></td></tr>
			       <tr>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>Receipt No.</b></nobr></td>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
			           <td  class="contenttab_internal_rows" nowrap width="2%">  
			                <input name="receiptNo" id="receiptNo" class="inputbox" style="width:220px" type="text">  
			           </td>  
			              <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" style="padding-left:10px;">
                          <nobr><b><u><a href="" alt="Click to clear date" title="Click to clear date" onclick="document.getElementById('fromDate').value='';document.getElementById('toDate').value='';">Receipt Date</a></u></b></nobr></td>
			           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
			           <a id="lk1" class="set_default_values">Set Default Values for Report Parameters</a>
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
			           <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Fee Payment Status</b></nobr></td>
			           <td class="contenttab_internal_rows" nowrap valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
			           <td  class="contenttab_internal_rows"  nowrap valgin="top" width="2%"> 
			             <select id="onlineStatus" name="onlineStatus" class="selectfield" style="width:205px">
			             	<option value="" >Select</option>
			             	   	<option value="1" selected="selected">Success</option>
			             		<option value="2">Failed</option>
			             </select> 
			           </td>       
  
					</tr>						
					<tr><td height='10px'></td></tr>  
						<tr>
						    <td class="contenttab_internal_rows" nowrap valgin="top" colspan="10">
						      <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="left">       
						       <tr>
						         <td class="contenttab_internal_rows" nowrap valgin="top">
						            <b>Starting Record No.:</b>&nbsp;
						         </td>
						         <td class="contenttab_internal_rows" nowrap valgin="top"> 
						           <input id="startingRecord" name="startingRecord" class="inputbox1" maxlength="5" value="1" style="width:50px" type="text">
						         </td>
						         <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;">
						            <b>Show No. of Records in Print & CSV Report:</b>&nbsp;
						         </td>
						         <td class="contenttab_internal_rows" nowrap valgin="top"> 
						           <input id="totalRecords" name="totalRecords" class="inputbox1" maxlength="5" value="500" style="width:50px" type="text">
						         </td>
						       </tr>
						      </table>
						     </td>    
						</tr>
						<tr><td height='10px'></td></tr>  
						<tr>
						   <td class="contenttab_internal_rows" nowrap colspan="15" >  
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
                                        <td colspan="1" class="content_title">Online Fee Payment Status :</td>
                                        <td colspan="2" class="content_title" align="right">
                                        	<!-- 
                                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="tallyXMlData();return false;"/>&nbsp;
                                            -->
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
