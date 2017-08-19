<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR MEDICAL LEAVE UPLOAD 
// Author : Aditi Miglani
// Created on : 20 Sept 2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
         <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Upload Medical Leave Entries : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="uploadForm"  id="uploadForm" action="<?php echo HTTP_LIB_PATH;?>/MedicalLeave/uploadFile.php" method="post" enctype="multipart/form-data" style="display:inline" onsubmit="return false;" >
                   <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td class="contenttab_internal_rows"><b>Time Table</b></td>
                     <td class="padding">:</td>
                     <td class="padding">
                      <select size="1" class="selectField" name="labelId" id="labelId" >
                        <option value="" >Select</option>
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
                        ?>
                      </select>
                     </td>
                     <td class="contenttab_internal_rows" style="padding-left:10px;"><b>Choose File</b> (.xls extention)</td>
                     <td class="padding">:</td>
                     <td class="padding">
                      <input type="file" name="medicalLeaveFile" id="medicalLeaveFile" class="selectField" />
                     </td>
                     <td class="padding" style="padding-left:80px;">
                       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="return initAdd();" />
                     </td>
                    </tr> 
                   
		      <table align="center" border="0" cellpadding="0" width="100%">
			<tr> 
                           <td class="contenttab_internal_rows" align="left" colspan="20">
	                      <table width="100%" border="0px" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <td class="contenttab_internal_rows">
                                       <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                       <Label id='sampleFormat'>Expand Sample Format for .xls file and instructions</label></b></a>
                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                    </td>
                                 </tr> 
                                  <tr>
                                    <td class="contenttab_internal_rows" colspan="20" id='showSampleFormat' style="display:none">
                                    <nobr><br><span id='sampleInfo'>
				    <table border="1" cellpadding="0" cellspacing="0" width="100%">
                                       <tr>
                                           <td class="contenttab_internal_rows"><b>Sr.No</b></td>
		                           <td class="contenttab_internal_rows"><b>Date</b></td>
                           		   <td class="contenttab_internal_rows"><b>Roll No.</b></td>
                           		   <td class="contenttab_internal_rows"><b>Lectures</b></td>
                           
                         	       </tr>
                      		       <tr>
                          		   <td class="contenttab_internal_rows">1</td>
                           		   <td class="contenttab_internal_rows">22.09.2011</td>
		                           <td class="contenttab_internal_rows">E105052</td>
                		           <td class="contenttab_internal_rows">1~8</td>
                		       </tr>
                         	       <tr>
                          		   <td class="contenttab_internal_rows">2</td>
	                       		   <td class="contenttab_internal_rows">25.09.2011</td>
		                           <td class="contenttab_internal_rows">E105030</td>
                		           <td class="contenttab_internal_rows">1,4,6~8</td>
                		       </tr>
                                   </table>
					<br/>
	 			   <b><u>***Please Note***</u><b><br/>
	         	 <b><font color="red">1. All the columns are <u>MANDATORY</u></font></b><br/>
	                           <b><font color="red">2. Columns must be in the same order as in the above mentioned format.</b><br/>
			 	   <b><font color="red">3. Not even a single column should be removed or added.</font></b><br/>
				   <b><font color="red">4. The "Lectures" can be seperated by comma(,) or continuation can be represented by tilde(~).</font></b><br/>
		                   </span></nobr>
                                  </td>
                                </tr> 
                              </table>
                            </td>
                          </tr>
			</table>
                   </table>
                   <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
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
