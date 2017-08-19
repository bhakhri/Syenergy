<?php
//This file creates Html Form output in Subjects Module 
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>			

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top" class="title">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
                <td valign="top" class="title">
                    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td valign="top">
    
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
                <td valign="top" class="content" align="center">
                    <!-- form table starts -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                        <tr>
                            <td valign="top" class="contenttab_row1">
                                  <table align="center" border="0" width="100%" cellspacing="0px" cellpadding="0px">
                                        <tr>             
                                            <td width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;Allow IP Nos.</B></nobr></td>
                                            <td width="2%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b><?php echo REQUIRED_FIELD ?></nobr></td>
                                            <td width="96%" class="contenttab_internal_rows">
                                                  <nobr><textarea cols="90" rows="3" class="inputbox1" id="allowIp" name="allowIp" ></textarea></nobr>
                                                  <br>
                                                  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
                                    To enter Multiple IP please use COMMA( , ). <br>To Generate continuous series of IP use TILDE( ~ ) sign.
                                    (For eg. 250.250.12.5~7 will generate 250.250.12.5, 250.250.12.6, 250.250.12.7)
                                </span>
                                               </td>    
                                            </tr>
                                            <tr>
                                              <td class="padding" align="center" colspan="2"></td>
                                              <td class="padding" align="left" style="padding-left:220px">
                                               <nobr>
                                                <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="validateAddForm(); return false;" />
                                                       </nobr>  
                                               </td> 
                                            </tr>  
                                            <tr>
			<td colspan="4" >
				<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
                <tr>
                <td class="contenttab_internal_rows">
                  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: red;"> 
				  <u>Note:</u><br>
				   Provide the Public/Static IP given by Internet Service Provider through which internet will be accessed.<br>
                  </span>
                   </td>	
					</tr>  
                   </table>
                   <br>
                                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
	 <td class="contenttab_border" height="20">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
            <tr>
                <td class="content_title"  width="90%">Allowed IP No:</td>
                <td valign="top" align="right" width="10%" nowrap>
                  <form action="" method="" name="searchForm">
                    <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                    <img src="<?php echo IMG_HTTP_PATH; ?>/search1.gif" value="Search" name="submit" style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" >&nbsp;     
                  </form>
                </td>
            </tr>
            </table>
                </td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" >
                                    <div id="scroll2" style="overflow:auto; width:1000px; height:410px; vertical-align:top;">
                                        <div id="results" style="width:98%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                   	<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title" valign="middle" align="right" width="20%">
			
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/delete_selected_IPs.gif" onClick="unBlock();" >&nbsp;
                                            
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
											</td>
										</tr>
									</table>
								</td>
							</tr>           
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
     </form>

